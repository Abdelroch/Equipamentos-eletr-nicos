<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'geral@mklda.ao')->first();
        $gestor = User::where('email', 'gestor@mklda.ao')->first();

        Budget::create([
            'balance' => 100000.00,
            'description' => 'Depósito inicial',
            'transaction_type' => 'Depósito',
            'amount' => 100000.00,
            'transaction_date' => '2025-01-01',
            'user_id' => $admin->id,
        ]);

        Budget::create([
            'balance' => 95000.00,
            'description' => 'Saque para pagamento de fornecedor',
            'transaction_type' => 'Saque',
            'amount' => -5000.00,
            'transaction_date' => '2025-02-01',
            'user_id' => $gestor->id,
        ]);

        Budget::create([
            'balance' => 120000.00,
            'description' => 'Empréstimo bancário. Taxa de juros: 5%',
            'transaction_type' => 'Empréstimo',
            'amount' => 25000.00,
            'transaction_date' => '2025-03-01',
            'user_id' => $admin->id,
        ]);
    }
}
