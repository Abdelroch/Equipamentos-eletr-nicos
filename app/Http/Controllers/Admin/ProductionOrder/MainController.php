<?php

namespace App\Http\Controllers\Admin\ProductionOrder;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $ordens = ProductionOrder::with('product')->get();
        $produtos = Product::all();
        return view('admin.production_orders.list.index', ['data' => ['ordens_producao' => $ordens, 'produtos' => $produtos]]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string|in:Agendado,Em Andamento,Concluído',
            'raw_materials' => 'nullable|string',
            'cost' => 'required|numeric|min:0', // Novo campo para custo de produção
        ]);

        DB::transaction(function () use ($validated, $request) {
            $order = ProductionOrder::create($validated);

            $product = Product::findOrFail($validated['product_id']);
            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel + $validated['quantity']]);

            $currentBalance = Budget::sum('amount') ?? 0;
            Budget::create([
                'balance' => $currentBalance - $validated['cost'],
                'description' => "Ordem de produção para produto {$product->nome}",
                'transaction_type' => 'Despesa',
                'amount' => -$validated['cost'],
                'transaction_date' => $validated['start_date'],
                'user_id' => auth()->id(),
            ]);

            $order->update(['budget_id' => Budget::latest()->first()->id]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Criação de Ordem de Produção',
                'descricao' => "Ordem de produção para produto {$product->nome} criada.",
            ]);
        });

        return redirect()->route('admin.gestao.ordens-producao')->with('ordemProducaoCadastrada', 'Ordem de produção cadastrada com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $order = ProductionOrder::findOrFail($id);
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string|in:Agendado,Em Andamento,Concluído',
            'raw_materials' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request, $order) {
            $oldProduct = Product::findOrFail($order->product_id);
            $oldProduct->update(['quantidade_disponivel' => $oldProduct->quantidade_disponivel - $order->quantity]);

            $product = Product::findOrFail($validated['product_id']);
            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel + $validated['quantity']]);

            if ($order->budget_id) {
                $oldBudget = Budget::findOrFail($order->budget_id);
                $currentBalance = Budget::sum('amount') - $oldBudget->amount;
                $oldBudget->delete();
            } else {
                $currentBalance = Budget::sum('amount') ?? 0;
            }

            $order->update($validated);

            Budget::create([
                'balance' => $currentBalance - $validated['cost'],
                'description' => "Atualização da ordem de produção para produto {$product->nome}",
                'transaction_type' => 'Despesa',
                'amount' => -$validated['cost'],
                'transaction_date' => $validated['start_date'],
                'user_id' => auth()->id(),
            ]);

            $order->update(['budget_id' => Budget::latest()->first()->id]);

            Log::create([
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'accao' => 'Atualização de Ordem de Produção',
                'descricao' => "Ordem de produção para produto {$product->nome} atualizada.",
            ]);
        });

        return redirect()->route('admin.gestao.ordens-producao')->with('ordemProducaoAtualizada', 'Ordem de produção atualizada com sucesso.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $order = ProductionOrder::findOrFail($id);
            $product = Product::findOrFail($order->product_id);
            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel - $order->quantity]);

            if ($order->budget_id) {
                $budget = Budget::findOrFail($order->budget_id);
                $budget->delete();
            }

            Log::create([
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'accao' => 'Exclusão de Ordem de Produção',
                'descricao' => "Ordem de produção para produto {$product->nome} removida.",
            ]);

            $order->delete();
        });

        return redirect()->route('admin.gestao.ordens-producao')->with('ordemProducaoRemovida', 'Ordem de produção removida com sucesso.');
    }
}
