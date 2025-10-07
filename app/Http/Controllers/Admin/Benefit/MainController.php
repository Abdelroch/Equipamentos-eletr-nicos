<?php

namespace App\Http\Controllers\Admin\Benefit;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Employee;
use App\Models\Budget;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use App\Services\BudgetService;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
class MainController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $beneficios = Benefit::with('employee')->get();
        $funcionarios = Employee::all();
        return view('admin.benefits.list.index', ['data' => ['beneficios' => $beneficios, 'funcionarios' => $funcionarios]]);
    }
public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:Receita,Despesa',
            'transaction_date' => 'required|date',
            'category' => 'nullable|string|max:100',
        ]);

        try {
            $budgetId = BudgetService::updateBudget([
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'transaction_type' => $validated['transaction_type'],
                'transaction_date' => $validated['transaction_date'],
                'user_id' => auth()->id(),
                'category' => $validated['category'],
            ]);

            // Log da ação
            \App\Models\Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Criação',
                'descricao' => "Transação #{$budgetId} ({$validated['transaction_type']}) criada.",
            ]);

            return redirect()->back()->with('success', 'Transação registrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $benefit = Benefit::findOrFail($id);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'benefit_type' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $benefit) {
            if ($benefit->budget_id && $benefit->amount) {
                $oldBudget = Budget::findOrFail($benefit->budget_id);
                $currentBalance = Budget::sum('amount') - $oldBudget->amount;
                $oldBudget->delete();
            } else {
                $currentBalance = Budget::sum('amount') ?? 0;
            }

            $benefit->update($validated);

            if ($validated['amount']) {
                Budget::create([
                    'balance' => $currentBalance - $validated['amount'],
                    'description' => "Atualização do benefício {$benefit->benefit_type} para funcionário ID {$benefit->employee_id}",
                    'transaction_type' => 'Despesa',
                    'amount' => -$validated['amount'],
                    'transaction_date' => $validated['start_date'],
                    'user_id' => auth()->id(),
                ]);

                $benefit->update(['budget_id' => Budget::latest()->first()->id]);
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de Benefício',
                'descricao' => "Benefício {$benefit->benefit_type} atualizado.",
            ]);
        });

        return redirect()->route('admin.gestao.beneficios')->with('beneficioAtualizado', 'Benefício atualizado com sucesso.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $benefit = Benefit::findOrFail($id);
            if ($benefit->budget_id) {
                $budget = Budget::findOrFail($benefit->budget_id);
                $budget->delete();
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de Benefício',
                'descricao' => "Benefício {$benefit->benefit_type} removido.",
            ]);

            $benefit->delete();
        });

        return redirect()->route('admin.gestao.beneficios')->with('beneficioRemovido', 'Benefício removido com sucesso.');
    }
}
