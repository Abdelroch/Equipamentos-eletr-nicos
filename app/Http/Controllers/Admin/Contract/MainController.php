<?php

namespace App\Http\Controllers\Admin\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        $contratos = Contract::with('client')->get();
        $clientes = Client::all();
        $financeiro = Employee::all();
        
        return view('admin.contracts.list.index', ['data' => ['contratos' => $contratos, 'clientes' => $clientes, 'financeiro' => $financeiro]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sign_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|in:Ativo,Expirado,Terminado',
            'client_id' => 'required|exists:client,id',
        ]);

        $contrato = Contract::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Criação de Contrato',
            'descricao' => "Contrato {$contrato->title} criado.",
        ]);

        return redirect()->route('admin.gestao.contratos')->with('contratoCadastrado', 'Contrato cadastrado');
    }

    public function update(Request $request, $id)
    {
        $contrato = Contract::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sign_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'status' => 'required|string|in:Ativo,Expirado,Terminado',
            'client_id' => 'required|exists:client,id',
        ]);

        $contrato->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Atualização de Contrato',
            'descricao' => "Contrato {$contrato->title} atualizado.",
        ]);

        return redirect()->route('admin.gestao.contratos')->with('contratoAtualizado', 'Contrato atualizado');
    }

    public function destroy($id)
    {
        $contrato = Contract::findOrFail($id);
        $contrato->delete();

        Log::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'accao' => 'Exclusão de Contrato',
            'descricao' => "Contrato {$contrato->title} removido.",
        ]);

        return redirect()->route('admin.gestao.contratos')->with('contratoRemovido', 'Contrato removido');
    }
}
