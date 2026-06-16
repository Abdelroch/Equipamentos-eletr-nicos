<?php
namespace App\Services;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetService
{
    public static function updateBudget(
        float $amount,
        string $description,
        string $transactionType,
        string $transactionDate,
        int $userId,
        ?string $category = null
    ) {
        return DB::transaction(function () use ($amount, $description, $transactionType, $transactionDate, $userId, $category) {

            // Obtém o saldo atual
            $currentBalance = (float) (Budget::sum('amount') ?? 0);

            // Calcula o novo saldo
            $newBalance = $transactionType === 'Receita'
                ? $currentBalance + $amount
                : $currentBalance - $amount;

            // Validação de saldo insuficiente
            if ($transactionType === 'Despesa' && $newBalance < 0) {
                throw new \Exception('Saldo insuficiente para a transação.');
            }

            // Validação de limite por categoria (se aplicável)
            if (!empty($category)) {
                $monthlyLimit = self::getCategoryLimit($category, $transactionDate);
                $spentThisMonth = self::getMonthlySpending($category, $transactionDate);
                if ($spentThisMonth + $amount > $monthlyLimit) {
                    throw new \Exception("Limite mensal para a categoria {$category} excedido.");
                }
            }

            // Cria a transação
            $budget = Budget::create([
                'balance'          => $newBalance,
                'description'      => $description,
                'transaction_type' => $transactionType,
                'amount'           => $transactionType === 'Receita' ? $amount : -$amount,
                'transaction_date' => $transactionDate,
                'user_id'          => $userId,
                'category'         => $category,
            ]);

            // Notificação de saldo baixo
            if ($newBalance < 10000) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->notify(new \App\Notifications\LowBalanceNotification($newBalance));
                }
            }

            return $budget->id;
        });
    }

    private static function getCategoryLimit($category, $date)
    {
        $limits = [
            'Imposto' => 100000,
            'Salário' => 500000,
            'Projeto' => 200000,
        ];
        return $limits[$category] ?? 1000000;
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
