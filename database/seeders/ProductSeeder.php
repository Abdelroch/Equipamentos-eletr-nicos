<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
{
    $supplier1 = Supplier::first();
    $supplier2 = Supplier::skip(1)->first();

    Product::create([
        'nome'                  => 'Produto Exemplo 1',
        'descricao'             => 'Descrição do produto 1',
        'preco'                 => 150000.00,
        'quantidade_disponivel' => 50,
        'categoria'             => 'laptops',
        'cover_image'           => null,   // sem imagem por defeito
        'imagens'               => [],
        'id_fornecedor'         => $supplier1->id,
    ]);

    Product::create([
        'nome'                  => 'Produto Exemplo 2',
        'descricao'             => 'Descrição do produto 2',
        'preco'                 => 85000.00,
        'quantidade_disponivel' => 30,
        'categoria'             => 'monitors',
        'cover_image'           => null,
        'imagens'               => [],
        'id_fornecedor'         => $supplier2->id,
    ]);
}
}
