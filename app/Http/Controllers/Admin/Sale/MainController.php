<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Log;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->get()->sortBy('nome')->values();
        $products = Product::orderBy('nome')->get();
        $sales = Sale::with(['customer.user', 'product'])->orderBy('created_at', 'desc')->get();

        return view('admin.sales.list.index', compact('sales', 'customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'id_product'  => 'required|exists:product,id',
            'quantidade'  => 'required|integer|min:1',
            'data_venda'  => 'required|date',
        ]);

        $sale = DB::transaction(function () use ($validated, $request) {
            $product = Product::findOrFail($validated['id_product']);
            $customer = Customer::findOrFail($validated['customer_id']);
            $total = $product->preco * $validated['quantidade'];

            if ($product->quantidade_disponivel < $validated['quantidade']) {
                throw new \Exception('Estoque insuficiente para o produto.');
            }

            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel - $validated['quantidade']]);

            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'id_product'  => $validated['id_product'],
                'quantidade'  => $validated['quantidade'],
                'data_venda'  => $validated['data_venda'],
                'total'       => $total,
            ]);

            $budgetId = BudgetService::updateBudget(
                $total,
                "Venda do produto {$product->nome} para {$customer->nome}",
                'Receita',
                $validated['data_venda'],
                auth()->id()
            );

            $sale->update(['budget_id' => $budgetId]);

            Log::create([
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'accao'     => 'Criação de Venda',
                'descricao' => "Venda do produto {$product->nome} para {$customer->nome} na data {$sale->data_venda}.",
            ]);

            return $sale;
        });

        return redirect()->route('admin.gestao.vendas')->with('vendaCadastrado', 'Venda cadastrada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $validated = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'id_product'  => 'required|exists:product,id',
            'quantidade'  => 'required|integer|min:1',
            'data_venda'  => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $request, $sale) {
            $product = Product::findOrFail($validated['id_product']);
            $customer = Customer::findOrFail($validated['customer_id']);
            $total = $product->preco * $validated['quantidade'];

            $oldProduct = Product::findOrFail($sale->id_product);
            $oldProduct->update(['quantidade_disponivel' => $oldProduct->quantidade_disponivel + $sale->quantidade]);

            if ($product->quantidade_disponivel < $validated['quantidade']) {
                throw new \Exception('Estoque insuficiente para o produto.');
            }

            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel - $validated['quantidade']]);

            if ($sale->budget_id) {
                BudgetService::revertBudget($sale->budget_id);
            }

            $sale->update([
                'customer_id' => $validated['customer_id'],
                'id_product'  => $validated['id_product'],
                'quantidade'  => $validated['quantidade'],
                'data_venda'  => $validated['data_venda'],
                'total'       => $total,
            ]);

            $budgetId = BudgetService::updateBudget(
                $total,
                "Atualização da venda do produto {$product->nome} para {$customer->nome}",
                'Receita',
                $validated['data_venda'],
                auth()->id()
            );

            $sale->update(['budget_id' => $budgetId]);

            Log::create([
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'accao'     => 'Atualização de Venda',
                'descricao' => "Venda do produto {$product->nome} para {$customer->nome} atualizada.",
            ]);
        });

        return redirect()->route('admin.gestao.vendas')->with('vendaAtualizado', 'Venda atualizada com sucesso!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            $product = Product::findOrFail($sale->id_product);
            $customer = Customer::findOrFail($sale->customer_id);

            $product->update(['quantidade_disponivel' => $product->quantidade_disponivel + $sale->quantidade]);

            if ($sale->budget_id) {
                BudgetService::revertBudget($sale->budget_id);
            }

            Log::create([
                'user_id'   => auth()->id(),
                'ip'        => request()->ip(),
                'accao'     => 'Exclusão de Venda',
                'descricao' => "Venda do produto {$product->nome} para {$customer->nome} removida.",
            ]);

            $sale->delete();
        });

        return redirect()->route('admin.gestao.vendas')->with('vendaRemovido', 'Venda removida com sucesso!');
    }
}
