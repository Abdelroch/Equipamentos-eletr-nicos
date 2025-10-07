<?php

namespace App\Http\Controllers\Admin\Saques;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Financial;
use App\Models\Log;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $transactions = Budget::with('user')->whereIn('transaction_type', ['Saque', 'Empréstimo'])->get();
        $deletedTransactions = Budget::onlyTrashed()->whereIn('transaction_type', ['Saque', 'Empréstimo'])->get();
        return view('admin.withdrawals_loans.list.index', [
            'data' => [
                'transactions' => $transactions,
                'deletedTransactions' => $deletedTransactions,
                'currentBalance' => Budget::sum('amount') ?? 0,
            ]
        ]);
    }

    public function storeWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $budgetId = BudgetService::updateBudget(
                $validated['amount'],
                $validated['description'],
                'Saque',
                $validated['transaction_date'],
                auth()->id()
            );

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Saque',
                'descricao' => "Saque de {$validated['amount']} realizado.",
            ]);
        });

        return redirect()->route('admin.gestao.withdrawals-loans')->with('withdrawalCadastrado', 'Saque registrado com sucesso.');
    }

    public function storeLoan(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'interest_rate' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $budgetId = BudgetService::updateBudget(
                $validated['amount'],
                $validated['description'] . (isset($validated['interest_rate']) ? " Taxa de juros: {$validated['interest_rate']}%" : ''),
                'Empréstimo',
                $validated['transaction_date'],
                auth()->id()
            );

            $budget = Budget::findOrFail($budgetId);

            Financial::create([
                'transaction_type' => 'Despesa',
                'amount' => $validated['amount'] * (1 + ($validated['interest_rate'] ?? 0) / 100),
                'description' => "Pagamento de empréstimo: {$validated['description']}",
                'due_date' => $validated['due_date'],
                'status' => 'Pendente',
                'budget_id' => $budget->id,
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Empréstimo',
                'descricao' => "Empréstimo de {$validated['amount']} registrado.",
            ]);
        });

        return redirect()->route('admin.gestao.withdrawals-loans')->with('loanCadastrado', 'Empréstimo registrado com sucesso.');
    }

    public function edit($id)
    {
        // Não precisamos de uma view separada; retornamos os dados para o modal
        $transaction = Budget::with('financial')->findOrFail($id);
        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Budget::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'interest_rate' => 'nullable|numeric|min:0',
            'due_date' => $transaction->transaction_type === 'Empréstimo' ? 'required|date|after:today' : 'nullable|date|after:today',
        ]);

        DB::transaction(function () use ($validated, $request, $transaction) {
            BudgetService::revertBudget($transaction->id);

            $budgetId = BudgetService::updateBudget(
                $validated['amount'],
                $validated['description'] . (isset($validated['interest_rate']) && $transaction->transaction_type === 'Empréstimo' ? " Taxa de juros: {$validated['interest_rate']}%" : ''),
                $transaction->transaction_type,
                $validated['transaction_date'],
                auth()->id()
            );

            $transaction->update(['budget_id' => $budgetId]);

            if ($transaction->transaction_type === 'Empréstimo') {
                $financial = Financial::where('budget_id', $transaction->id)->first();
                if ($financial) {
                    $financial->update([
                        'amount' => $validated['amount'] * (1 + ($validated['interest_rate'] ?? 0) / 100),
                        'description' => "Pagamento de empréstimo: {$validated['description']}",
                        'due_date' => $validated['due_date'],
                    ]);
                }
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de ' . $transaction->transaction_type,
                'descricao' => "{$transaction->transaction_type} de {$validated['amount']} atualizado.",
            ]);
        });

        return redirect()->route('admin.gestao.withdrawals-loans')->with('transactionAtualizada', 'Transação atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $transaction = Budget::findOrFail($id);

        DB::transaction(function () use ($transaction, $id) {
            BudgetService::revertBudget($transaction->id);
            $transaction->delete();

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de ' . $transaction->transaction_type,
                'descricao' => "{$transaction->transaction_type} de {$transaction->amount} removido.",
            ]);
        });

        return redirect()->route('admin.gestao.withdrawals-loans')->with('transactionRemovida', 'Transação removida com sucesso.');
    }

    public function restore($id)
    {
        $transaction = Budget::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($transaction, $id) {
            $budgetId = BudgetService::updateBudget(
                abs($transaction->amount),
                $transaction->description,
                $transaction->transaction_type,
                $transaction->transaction_date,
                $transaction->user_id
            );

            $transaction->restore();
            $transaction->update(['budget_id' => $budgetId]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Restauração de ' . $transaction->transaction_type,
                'descricao' => "{$transaction->transaction_type} de {$transaction->amount} restaurado.",
            ]);
        });

        return redirect()->route('admin.gestao.withdrawals-loans')->with('transactionRestaurada', 'Transação restaurada com sucesso.');
    }
}
