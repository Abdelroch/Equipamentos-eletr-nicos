<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::first();

        Contract::create([
            'title' => 'Contrato Exemplo 1',
            'description' => 'Contrato de prestação de serviços',
            'sign_date' => '2025-01-01',
            'expiration_date' => '2025-12-31',
            'status' => 'Active',
            'client_id' => $client->id,
            'document_path' => 'contracts/contract1.pdf',
        ]);

        Contract::create([
            'title' => 'Contrato Exemplo 2',
            'description' => 'Contrato de fornecimento',
            'sign_date' => '2025-02-01',
            'expiration_date' => null,
            'status' => 'Active',
            'client_id' => $client->id,
            'document_path' => 'contracts/contract2.pdf',
        ]);
    }
}
