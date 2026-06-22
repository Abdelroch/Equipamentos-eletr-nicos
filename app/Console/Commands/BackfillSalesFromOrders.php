<?php

namespace App\Console\Commands;

use App\Models\OrderNegotiation;
use App\Models\Sale;
use App\Services\ClientService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillSalesFromOrders extends Command
{
    protected $signature = 'sales:backfill-from-orders {--dry-run : Mostra o que seria feito sem gravar nada}';

    protected $description = 'Cria registos em Sale para encomendas já confirmadas que ainda não têm venda associada';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $pendentes = OrderNegotiation::where('status', 'confirmed')
            ->whereNull('sale_id')
            ->with('product', 'user')
            ->get();

        if ($pendentes->isEmpty()) {
            $this->info('Nenhuma encomenda confirmada sem venda associada. Nada a fazer.');
            return self::SUCCESS;
        }

        $this->info("Encontradas {$pendentes->count()} encomenda(s) confirmada(s) sem venda associada.");

        foreach ($pendentes as $order) {
            if (!$order->product) {
                $this->warn("⚠️  Encomenda #{$order->id}: produto associado não existe mais. A saltar.");
                continue;
            }
            if (!$order->user) {
                $this->warn("⚠️  Encomenda #{$order->id}: utilizador associado não existe mais. A saltar.");
                continue;
            }

            if ($dryRun) {
                $this->line("[dry-run] Criaria Sale para Encomenda #{$order->id} — Produto: {$order->product->nome}, Cliente: {$order->user->name}, Total: {$order->total_price}");
                continue;
            }

            DB::transaction(function () use ($order) {
                $client = ClientService::resolveForUser($order->user);

                $sale = Sale::create([
                    'id_cliente'           => $client->id,
                    'id_product'           => $order->product_id,
                    'quantidade'           => $order->quantity,
                    'data_venda'           => $order->reviewed_at?->toDateString() ?? $order->updated_at->toDateString(),
                    'total'                => $order->total_price,
                    'budget_id'            => $order->budget_id,
                    'order_negotiation_id' => $order->id,
                ]);

                $order->update(['sale_id' => $sale->id]);

                $this->info("✅ Encomenda #{$order->id} → Venda #{$sale->id} criada.");
            });
        }

        return self::SUCCESS;
    }
}
