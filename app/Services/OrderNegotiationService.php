<?php

namespace App\Services;

use App\Models\OrderNegotiation;
use App\Models\Product;
use App\Models\Budget;
use App\Models\Log;
use App\Models\User;
use App\Notifications\NewOrderReceived;
use App\Notifications\OrderAccepted;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderReleased;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class OrderNegotiationService
{
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

        $order = OrderNegotiation::create($data);

        // Notifica todos os admins — fora da lock do produto, mas ainda
        // dentro da transação para garantir consistência se algo falhar.
        $admins = User::where('access_level', 'admin')->get();
        if ($admins->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($admins, new NewOrderReceived($order));
        }

        return $order;
    });
}

    public static function accept(OrderNegotiation $order, ?int $adminId = null): OrderNegotiation
    {
        return DB::transaction(function () use ($order, $adminId) {
            $product = Product::where('id', $order->product_id)->lockForUpdate()->firstOrFail();

            if ($product->quantidade_disponivel < $order->quantity) {
                throw new \RuntimeException(
                    "Stock insuficiente para reservar. Disponível: {$product->quantidade_disponivel}, necessário: {$order->quantity}."
                );
            }

            $novaQuantidade = $product->quantidade_disponivel - $order->quantity;

            $product->update([
                'quantidade_disponivel' => $novaQuantidade,
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

            // ⬇️ NOVO: avisa o cliente que a proposta foi aceite e tem prazo para pagar
            $order->user->notify(new OrderAccepted($order));

            return $order->fresh();
        });
    }

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

            // ⬇️ NOVO: avisa o cliente que a compra foi concluída
            $order->user->notify(new OrderConfirmed($order));

            return $order->fresh();
        });
    }

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

            // ⬇️ NOVO: avisa o cliente — cobre rejected, cancelled, E expired (scheduler incluído)
            $order->user->notify(new OrderReleased($order));

            return $order->fresh();
        });
    }

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
