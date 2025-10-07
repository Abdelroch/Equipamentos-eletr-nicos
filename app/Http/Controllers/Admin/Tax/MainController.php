<?php

namespace App\Http\Controllers\Admin\Tax;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Models\Sale;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
class MainController extends Controller
{
    public function index()
    {
        $impostos = Tax::all();
        $vendas = Sale::all(); // Substitua por sua lógica de busca de vendas
        return view('admin.taxes.list.index', [
            'data' => [
                'impostos' => $impostos,
                'vendas' => $vendas
            ]
        ]);
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|string|in:Pendente,Pago',
            'sale_id' => 'nullable|exists:sale,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $tax = Tax::create($validated);

            if ($validated['status'] === 'Pago') {
                $currentBalance = Budget::sum('amount') ?? 0;
                Budget::create([
                    'balance' => $currentBalance - $validated['amount'],
                    'description' => "Pagamento do imposto {$tax->tax_type}" . ($tax->sale_id ? " (Venda #{$tax->sale_id})" : ''),
                    'transaction_type' => 'Despesa',
                    'amount' => -$validated['amount'],
                    'transaction_date' => $validated['due_date'],
                    'user_id' => auth()->id(),
                ]);

                $tax->update(['budget_id' => Budget::latest()->first()->id]);
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Criação de Imposto',
                'descricao' => "Imposto {$tax->tax_type} criado.",
            ]);
        });

        return redirect()->route('admin.gestao.impostos')->with('impostoCadastrado', 'Imposto cadastrado com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);
        $validated = $request->validate([
            'tax_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|string|in:Pendente,Pago',
            'sale_id' => 'nullable|exists:sale,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $tax) {
            if ($tax->budget_id && $tax->status === 'Pago') {
                $oldBudget = Budget::findOrFail($tax->budget_id);
                $currentBalance = Budget::sum('amount') - $oldBudget->amount;
                $oldBudget->delete();
            } else {
                $currentBalance = Budget::sum('amount') ?? 0;
            }

            $tax->update($validated);

            if ($validated['status'] === 'Pago') {
                Budget::create([
                    'balance' => $currentBalance - $validated['amount'],
                    'description' => "Atualização do imposto {$tax->tax_type}" . ($tax->sale_id ? " (Venda #{$tax->sale_id})" : ''),
                    'transaction_type' => 'Despesa',
                    'amount' => -$validated['amount'],
                    'transaction_date' => $validated['due_date'],
                    'user_id' => auth()->id(),
                ]);

                $tax->update(['budget_id' => Budget::latest()->first()->id]);
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de Imposto',
                'descricao' => "Imposto {$tax->tax_type} atualizado.",
            ]);
        });

        return redirect()->route('admin.gestao.impostos')->with('impostoAtualizado', 'Imposto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $tax = Tax::findOrFail($id);
            if ($tax->budget_id) {
                $budget = Budget::findOrFail($tax->budget_id);
                $budget->delete();
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de Imposto',
                'descricao' => "Imposto {$tax->tax_type} removido.",
            ]);

            $tax->delete();
        });

        return redirect()->route('admin.gestao.impostos')->with('impostoRemovido', 'Imposto removido com sucesso.');
    }
}
