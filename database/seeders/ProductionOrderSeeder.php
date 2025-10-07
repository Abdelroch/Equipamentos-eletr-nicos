<?php

namespace Database\Seeders;

use App\Models\ProductionOrder;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductionOrderSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();

        ProductionOrder::create([
            'product_id' => $product->id,
            'quantity' => 100,
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-15',
            'status' => 'In Progress',
            'raw_materials' => json_encode(['material1' => '10kg', 'material2' => '5kg']),
        ]);

        ProductionOrder::create([
            'product_id' => $product->id,
            'quantity' => 50,
            'start_date' => '2025-04-01',
            'end_date' => null,
            'status' => 'Scheduled',
            'raw_materials' => json_encode(['material1' => '5kg']),
        ]);
    }
}
