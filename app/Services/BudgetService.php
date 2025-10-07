<?php
namespace App\Services;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetService
{
    public static function updateBudget($data)
    {
        return DB::transaction(function () use ($data) {
            // Obtém o saldo atual
            $currentBalance = Budget::sum('amount') ?? 0;

            // Calcula o novo saldo
            $amount = $data['amount'];
            $transactionType = $data['transaction_type'];
            $newBalance = $transactionType === 'Receita' ? $currentBalance + $amount : $currentBalance - $amount;

            // Validação de saldo insuficiente
            if ($transactionType === 'Despesa' && $newBalance < 0) {
                throw new \Exception('Saldo insuficiente para a transação.');
            }

            // Validação de limite por categoria (se aplicável)
            if (!empty($data['category'])) {
                $monthlyLimit = self::getCategoryLimit($data['category'], $data['transaction_date']);
                $spentThisMonth = self::getMonthlySpending($data['category'], $data['transaction_date']);
                if ($spentThisMonth + $amount > $monthlyLimit) {
                    throw new \Exception("Limite mensal para a categoria {$data['category']} excedido.");
                }
            }

            // Cria a transação
            $budget = Budget::create([
                'balance' => $newBalance,
                'description' => $data['description'],
                'transaction_type' => $transactionType,
                'amount' => $transactionType === 'Receita' ? $amount : -$amount,
                'transaction_date' => $data['transaction_date'],
                'user_id' => $data['user_id'],
                'category' => $data['category'] ?? null,
            ]);

            // Dispara notificação se saldo estiver baixo
            if ($newBalance < 10000) { // Exemplo: limite de 10.000 KZ
                $user = \App\Models\User::find($data['user_id']);
                $user->notify(new \App\Notifications\LowBalanceNotification($newBalance));
            }

            return $budget->id;
        });
    }

    private static function getCategoryLimit($category, $date)
    {
        // Defina limites no config ou banco de dados
        $limits = [
            'Imposto' => 100000,
            'Salário' => 500000,
            'Projeto' => 200000,
        ];
        return $limits[$category] ?? 1000000; // Padrão
    }

    private static function getMonthlySpending($category, $date)
    {
        return Budget::where('category', $category)
            ->whereYear('transaction_date', Carbon::parse($date)->year)
            ->whereMonth('transaction_date', Carbon::parse($date)->month)
            ->where('transaction_type', 'Despesa')
            ->sum('amount') ?? 0;
    }

    public static function revertBudget($budgetId)
    {
        return DB::transaction(function () use ($budgetId) {
            $budget = Budget::findOrFail($budgetId);
            $currentBalance = Budget::sum('amount') - $budget->amount;
            $budget->delete();
            return $currentBalance;
        });
    }
}
