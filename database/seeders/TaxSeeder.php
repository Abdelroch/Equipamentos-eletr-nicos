<?php

namespace Database\Seeders;

use App\Models\Tax;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $sale = Sale::first();

        Tax::create([
            'tax_type' => 'IVA',
            'amount' => 70.00,
            'due_date' => '2025-06-30',
            'status' => 'Pending',
            'sale_id' => $sale->id,
            'notes' => 'Imposto sobre venda',
        ]);

        Tax::create([
            'tax_type' => 'Imposto de Renda',
            'amount' => 150.00,
            'due_date' => '2025-07-15',
            'status' => 'Paid',
            'sale_id' => null,
            'notes' => 'Imposto anual',
        ]);
    }
}
