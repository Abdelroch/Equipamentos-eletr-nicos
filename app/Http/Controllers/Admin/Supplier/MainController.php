<?php

namespace App\Http\Controllers\Admin\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $data['fornecedores'] = Supplier::orderBy('id', 'desc')->get();
        return view('admin.suppliers.table', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {


         /* CRIAÇÃO DE UM NOVO RESITRO NA TABELA Fornecedor */

         $fornecedor = Supplier::create([
            'nome' => $request->name,      // Atribua o valor do campo 'name' do request
            'numero' => $request->phone,    // Atribua o valor do campo 'phone' do request
            'email' => $request->email,    // Atribua o valor do campo 'email' do request
            'pais' => $request->country ,// Atribua o valor do campo 'province' do request
            'provincia' => $request->province ,// Atribua o valor do campo 'province' do request
            'imagem' => 'valor_teste',    // Atribua o valor do campo 'phone' do request
        ]);

        $user_id= auth()->id();


        /* CRIAÇÃO DO LOG */
        Log::create([
            'user_id' =>$user_id,
            'ip' => $request->ip(),
            'accao' => 'Cadastramento',
            'id_user' => $user_id,
            'descricao' => "Usuário {$user_id} cadastrou o/a fornecedor {$fornecedor->nome} com ID {$fornecedor->id}.",
        ]);

        return redirect()->back()->with('fornecedorCadastrado', 'Cadastrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fornecedor = Supplier::findOrFail($id);
        return view('admin.suppliers.edit.index', compact('fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

         // Localizar o fornecedor
    $fornecedor = Supplier::findOrFail($id);

    // Atualizar os dados do fornecedor
    $fornecedor->update([
            'nome' => $request->name,      // Atribua o valor do campo 'name' do request
            'numero' => $request->phone,    // Atribua o valor do campo 'phone' do request
            'email' => $request->email,    // Atribua o valor do campo 'email' do request
            'pais' => $request->country ,// Atribua o valor do campo 'province' do request
            'provincia' => $request->province ,// Atribua o valor do campo 'province' do request
            'imagem' => 'valor_teste',    // Atribua o valor do campo 'phone' do request
    ]);

    // Criar log
    $user_id = auth()->id();
    Log::create([
        'user_id' => $user_id,
        'ip' => $request->ip(),
        'accao' => 'Atualização',
        'id_user' => $user_id,
        'descricao' => "Usuário {$user_id} atualizou o/a cliente {$fornecedor->nome} com ID {$fornecedor->id}.",
    ]);

    return redirect()->back()->with('fornecedorAtualizado', 'Atualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {

                   // Localizar o fornecedor
    $fornecedor = Supplier::findOrFail($id);

    // Capturar o nome do produto antes de excluir
    $nomeFornecedor= $fornecedor->nome;

   // Remover o fornecedor
   $fornecedor->delete();


   // Criar log antes da remoção
   $user_id = auth()->id();
   Log::create([
       'user_id' => $user_id,
       'ip' => $request->ip(),
       'accao' => 'Eliminação',
       'id_user' => $user_id,
       'descricao' => "Usuário {$user_id} eliminou o cliente {$nomeFornecedor} com ID {$id}.",
   ]);


   return redirect()->back()->with('fornecedorRemovido', 'Eliminado');

    }
}
