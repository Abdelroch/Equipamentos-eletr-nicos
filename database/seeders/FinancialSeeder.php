<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Financial;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    public function run(): void
    {
        $sale = Sale::first();
        $loanBudget = Budget::where('transaction_type', 'Empréstimo')->first();

        Financial::create([
            'transaction_type' => 'Receita',
            'amount' => 500.00,
            'due_date' => '2025-06-01',
            'status' => 'Pendente',
            'description' => 'Pagamento de venda',
            'sale_id' => $sale->id,
            'budget_id' => null,
        ]);

        Financial::create([
            'transaction_type' => 'Despesa',
            'amount' => 300.00,
            'due_date' => '2025-05-15',
            'status' => 'Pago',
            'description' => 'Pagamento de fornecedor',
            'sale_id' => null,
            'budget_id' => null,
        ]);

        if ($loanBudget) {
            Financial::create([
                'transaction_type' => 'Despesa',
                'amount' => 26250.00, // 25.000 + 5% de juros
                'due_date' => '2025-09-01',
                'status' => 'Pendente',
                'description' => 'Pagamento de empréstimo bancário',
                'sale_id' => null,
                'budget_id' => $loanBudget->id,
            ]);
        }
    }
}
