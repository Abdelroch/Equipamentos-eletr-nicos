<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'nome' => 'Fornecedor Exemplo 1',
            'email' => 'fornecedor1@exemplo.com',
            'numero' => '111222333',
            'pais' => 'Angola',
            'provincia' => 'Luanda',
            'imagem' => 'suppliers/supplier1.jpg',
        ]);

        Supplier::create([
            'nome' => 'Fornecedor Exemplo 2',
            'email' => 'fornecedor2@exemplo.com',
            'numero' => '444555666',
            'pais' => 'Angola',
            'provincia' => 'Huambo',
            'imagem' => 'suppliers/supplier2.jpg',
        ]);
    }
}
