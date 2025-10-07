<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::first();
        $product = Product::first();

        Sale::create([
            'quantidade' => 5,
            'data_venda' => now()->subDays(10),
            'id_cliente' => $client->id,
            'id_product' => $product->id,
        ]);

        Sale::create([
            'quantidade' => 3,
            'data_venda' => now()->subDays(5),
            'id_cliente' => $client->id,
            'id_product' => $product->id,
        ]);
    }
}
