<?php

namespace App\Services;

use App\Models\OrderNegotiation;
use App\Models\Product;
use App\Models\Budget;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderNegotiationService
{
    /**
     * Horas de validade da reserva de stock após aceitação,
     * antes do cliente submeter o comprovativo.
     */
    public const RESERVATION_HOURS = 24;

    /**
     * Cria a negociação/compra em estado 'pending'.
     * NÃO mexe em stock — isso só acontece em accept().
     */
    public static function create(array $data): OrderNegotiation
    {
        return DB::transaction(function () use ($data) {
            $product = Product::where('id', $data['product_id'])->lockForUpdate()->firstOrFail();

            if ($product->quantidade_disponivel < ($data['quantity'] ?? 1)) {
                throw new \RuntimeException('Produto sem stock disponível.');
            }

            return OrderNegotiation::create($data);
        });
    }

    /**
     * Admin aceita a negociação/proposta de preço.
     * Reserva o stock e define o prazo de expiração da reserva.
     *
     * @throws \RuntimeException se não houver stock suficiente
     */
    public static function accept(OrderNegotiation $order, ?int $adminId = null): OrderNegotiation
    {
        return DB::transaction(function () use ($order, $adminId) {
            // Lock pessimista evita duas aceitações simultâneas venderem
            // a mesma última unidade em stock (race condition clássica).
            $product = Product::where('id', $order->product_id)->lockForUpdate()->firstOrFail();

            if ($product->quantidade_disponivel < $order->quantity) {
                throw new \RuntimeException(
                    "Stock insuficiente para reservar. Disponível: {$product->quantidade_disponivel}, necessário: {$order->quantity}."
                );
            }

            $novaQuantidade = $product->quantidade_disponivel - $order->quantity;

            $product->update([
                'quantidade_disponivel' => $novaQuantidade,
                // Só marca como 'reservado' se esgotou o stock;
                // se ainda houver unidades, o produto continua 'disponivel' para outros compradores.
                'estado_venda' => $novaQuantidade <= 0 ? 'reservado' : $product->estado_venda,
            ]);

            $order->update([
                'status' => 'accepted',
                'reserved_until' => Carbon::now()->addHours(self::RESERVATION_HOURS),
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
            ]);

            Log::create([
                'user_id' => $adminId,
                'ip' => request()->ip(),
                'accao' => 'Aceitação de Encomenda',
                'descricao' => "Encomenda #{$order->id} aceite. Stock reservado ({$order->quantity} un.) até {$order->reserved_until}.",
            ]);

            return $order->fresh();
        });
    }

    /**
     * Admin confirma o comprovativo de pagamento.
     * Aqui — e só aqui — a venda entra na contabilidade (Budget).
     */
    public static function confirm(OrderNegotiation $order, ?int $adminId = null, ?string $notes = null): OrderNegotiation
    {
        if (!in_array($order->status, ['awaiting_confirmation', 'accepted'])) {
            throw new \RuntimeException("Não é possível confirmar uma encomenda em estado '{$order->status}'.");
        }

        return DB::transaction(function () use ($order, $adminId, $notes) {
            $product = Product::where('id', $order->product_id)->lockForUpdate()->firstOrFail();

            $product->update([
                'estado_venda' => 'vendido',
            ]);

            $budgetId = BudgetService::updateBudget(
                (float) $order->total_price,
                "Venda do produto {$product->nome} (Encomenda #{$order->id})",
                'Receita',
                Carbon::now()->toDateString(),
                $adminId
            );

            $order->update([
                'status' => 'confirmed',
                'budget_id' => $budgetId,
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
            ]);

            Log::create([
                'user_id' => $adminId,
                'ip' => request()->ip(),
                'accao' => 'Confirmação de Encomenda',
                'descricao' => "Encomenda #{$order->id} confirmada. Receita de {$order->total_price} Kz lançada no orçamento (budget #{$budgetId}).",
            ]);

            return $order->fresh();
        });
    }

    /**
     * Admin rejeita a encomenda, OU o job de expiração actua.
     * Devolve ao stock a quantidade que tinha sido reservada — só se
     * a reserva alguma vez chegou a ser feita (status accepted/awaiting_confirmation).
     */
    public static function release(OrderNegotiation $order, string $newStatus, ?int $adminId = null, ?string $notes = null): OrderNegotiation
    {
        if (!in_array($newStatus, ['rejected', 'cancelled', 'expired'])) {
            throw new \InvalidArgumentException("Estado de libertação inválido: {$newStatus}");
        }

        return DB::transaction(function () use ($order, $newStatus, $adminId, $notes) {
            $wasReserved = in_array($order->status, ['accepted', 'awaiting_confirmation']);

            if ($wasReserved) {
                $product = Product::where('id', $order->product_id)->lockForUpdate()->firstOrFail();

                $novaQuantidade = $product->quantidade_disponivel + $order->quantity;

                $product->update([
                    'quantidade_disponivel' => $novaQuantidade,
                    'estado_venda' => $novaQuantidade > 0 ? 'disponivel' : $product->estado_venda,
                ]);
            }

            $order->update([
                'status' => $newStatus,
                'reserved_until' => null,
                'reviewed_at' => Carbon::now(),
                'reviewed_by' => $adminId,
                'admin_notes' => $notes,
            ]);

            $accao = match ($newStatus) {
                'rejected' => 'Rejeição de Encomenda',
                'cancelled' => 'Cancelamento de Encomenda',
                'expired' => 'Expiração de Reserva',
            };

            Log::create([
                'user_id' => $adminId,
                'ip' => request()->ip() ?? 'cli/scheduler',
                'accao' => $accao,
                'descricao' => "Encomenda #{$order->id}: {$accao}." . ($wasReserved ? " Stock devolvido ({$order->quantity} un.)." : ' (sem stock reservado a devolver)'),
            ]);

            return $order->fresh();
        });
    }

    /**
     * Chamado pelo scheduler. Liberta automaticamente todas as reservas
     * cujo prazo de 24h expirou sem submissão de comprovativo aprovado.
     *
     * Nota: encomendas em 'awaiting_confirmation' também expiram se o
     * admin demorar demais a rever — ajuste a regra abaixo se quiser
     * que awaiting_confirmation NUNCA expire automaticamente.
     */
    public static function releaseExpiredReservations(): int
    {
        $expiradas = OrderNegotiation::where('status', 'accepted')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<', Carbon::now())
            ->get();

        $count = 0;
        foreach ($expiradas as $order) {
            self::release($order, 'expired', null, 'Reserva expirada automaticamente após ' . self::RESERVATION_HOURS . 'h sem comprovativo.');
            $count++;
        }

        return $count;
    }
}
