<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSalesClientToCustomer extends Command
{
    protected $signature = 'sales:migrate-client-to-customer {--dry-run : Mostra o que seria feito sem alterar nada}';

    protected $description = 'Migra sale.id_cliente (tabela client, legada) para sale.customer_id (tabela customer)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $sales = Sale::whereNotNull('id_cliente')
            ->whereNull('customer_id')
            ->get();

        if ($sales->isEmpty()) {
            $this->info('Nenhuma venda pendente de migração. Tudo já está usando customer_id.');
            return self::SUCCESS;
        }

        $this->info("Encontradas {$sales->count()} vendas para migrar.");

        $clientToCustomerMap = [];

        DB::transaction(function () use ($sales, $dryRun, &$clientToCustomerMap) {
            foreach ($sales as $sale) {
                $clientId = $sale->id_cliente;

                if (!isset($clientToCustomerMap[$clientId])) {
                    $client = Client::find($clientId);

                    if (!$client) {
                        $this->warn("  Venda #{$sale->id}: client #{$clientId} não existe mais. Pulando.");
                        continue;
                    }

                    // Tenta achar um Customer já existente com o mesmo telefone
                    // (campo em comum entre os dois modelos, melhor sinal disponível).
                    $customer = Customer::where('phone_number', $client->numero)->first();

                    if (!$customer) {
                        // Não existe Customer correspondente — cria um "legado",
                        // preservando os dados do Client antigo. Sem user_id,
                        // pois não há como inferir com segurança qual User
                        // (se algum) corresponde a este cliente cadastrado
                        // manualmente antes da automação via registro.
                        $this->line("  Criando Customer legado para Client #{$client->id} ({$client->nome})");

                        if (!$dryRun) {
                            $customer = Customer::create([
                                'user_id'      => null,
                                'nome_legado'  => $client->nome,
                                'phone_number' => $client->numero,
                                'province'     => $client->provincia,
                                'nif'          => null,
                                'birth_date'   => null,
                            ]);
                        }
                    } else {
                        $this->line("  Client #{$client->id} ({$client->nome}) já corresponde a Customer #{$customer->id} (por telefone)");
                    }

                    $clientToCustomerMap[$clientId] = $customer->id ?? null;
                }

                $customerId = $clientToCustomerMap[$clientId];

                if (!$customerId) {
                    continue;
                }

                $this->line("  Venda #{$sale->id}: id_cliente {$clientId} -> customer_id {$customerId}");

                if (!$dryRun) {
                    $sale->update(['customer_id' => $customerId]);
                }
            }
        });

        if ($dryRun) {
            $this->warn('Modo --dry-run: nenhuma alteração foi salva. Rode sem --dry-run para aplicar.');
        } else {
            $this->info('Migração concluída.');
        }

        return self::SUCCESS;
    }
}
