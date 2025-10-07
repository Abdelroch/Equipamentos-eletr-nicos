<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        $financeiros = Financial::orderBy('id', 'desc')->get();
        return view('admin.financials.list.index', ['data' => ['financeiros' => $financeiros]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Receita,Despesa',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string|in:Salário,Venda,Compra,Imposto,Outros',
        ]);

        $financeiro = Financial::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Criação de Transação Financeira',
            'descricao' => "Transação {$financeiro->type} ({$financeiro->category}) criada.",
        ]);

        return redirect()->route('admin.gestao.financeiros')->with('financeiroCadastrado', 'Transação financeira cadastrada');
    }

    public function update(Request $request, $id)
    {
        $financeiro = Financial::findOrFail($id);
        $validated = $request->validate([
            'type' => 'required|string|in:Receita,Despesa',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string|in:Salário,Venda,Compra,Imposto,Outros',
        ]);

        $financeiro->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Atualização de Transação Financeira',
            'descricao' => "Transação {$financeiro->type} ({$financeiro->category}) atualizada.",
        ]);

        return redirect()->route('admin.gestao.financeiros')->with('financeiroAtualizado', 'Transação financeira atualizada');
    }

    public function destroy($id)
    {
        $financeiro = Financial::findOrFail($id);
        $financeiro->delete();

        Log::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'accao' => 'Exclusão de Transação Financeira',
            'descricao' => "Transação {$financeiro->type} ({$financeiro->category}) removida.",
        ]);

        return redirect()->route('admin.gestao.financeiros')->with('financeiroRemovido', 'Transação financeira removida');
    }
}
