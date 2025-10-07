<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;
use App\Traits\LogsActivity;

use Illuminate\Support\Facades\Validator;


class MainController extends Controller
{
    use LogsActivity;


    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $data['clientes'] = Client::orderBy('id', 'desc')->get();
        return view('admin.clients.table', ['data' => $data]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in DB.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'required|string|regex:/^\+?\d{9,15}$/',
            'province' => 'required|string|max:100',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',
            'phone.regex' => 'O número de telefone deve ter entre 9 e 15 dígitos.',
            'province.required' => 'A província é obrigatória.',
        ]);

        $client = Client::create([
            'nome' => $validated['name'],
            'numero' => $validated['phone'],
            'provincia' => $validated['province'],
            'imagem' => 'default.jpg',
        ]);

        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Cadastramento',
            'descricao' => "Cliente {$client->nome} (ID: {$client->id}) cadastrado.",
        ]);

        return redirect()->back()->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }



    /**
     * Update the specified resource in DB.
     */
    public function update(Request $request, $id)
    {

            // Validação dos dados
            /* $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'provincia' => 'required|string|max:100',

            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'phone.required' => 'O campo contacto é obrigatório.',
                'province.required' => 'O campo é obrigatório.',
            ]); */

        // Localizar o cliente
        $cliente = Client::findOrFail($id);

        // Atualizar os dados do cliente
        $cliente->update([
            'nome' => $request->name,
            'numero' => $request->phone,
            'provincia' => $request->province,
        ]);

        // Criar log
        $user_id = auth()->id();
        Log::create([
            'user_id' => $user_id,
            'ip' => $request->ip(),
            'accao' => 'Atualização',
            'id_user' => $user_id,
            'descricao' => "Usuário {$user_id} atualizou o/a cliente {$cliente->nome} com ID {$cliente->id}.",
        ]);

        return redirect()->back()->with('clienteAtualizado', 'Atualizado');

    }

    /**
     * Remove the specified resource from DB.
     */
    public function destroy(Request $request , $id)
    {
                // Localizar o cliente
        $cliente = Client::findOrFail($id);

        // Capturar o nome do produto antes de excluir
        $nomeCliente = $cliente->nome;

        // Remover o cliente
        $cliente->delete();


        // Criar log antes da remoção
        $user_id = auth()->id();
        Log::create([
            'user_id' => $user_id,
            'ip' => $request->ip(),
            'accao' => 'Eliminação',
            'id_user' => $user_id,
            'descricao' => "Usuário {$user_id} eliminou o cliente {$nomeCliente} com ID {$id}.",
        ]);


    return redirect()->back()->with('clienteRemovido', 'Eliminado');
    }

}
