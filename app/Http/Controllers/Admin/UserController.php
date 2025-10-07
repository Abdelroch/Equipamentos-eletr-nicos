<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Log; // Certifique-se de importar o modelo Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.list.index', ['data' => ['users' => $users]]);
    }public function show()
    {
        $users = User::all();
        return view('admin.users.list.index', ['data' => ['users' => $users]]);
    }

    public function create()
    {
        return view('admin.users.create.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Log da criação
        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Criação de Usuário',
            'id_user' => $user->id,
            'descricao' => "Usuário {$user->name} (ID: {$user->id}) criado por " . auth()->user()->name,
        ]);

        return redirect()->route('admin.gestao.usuarios')->with('userCadastrado', 'Cadastrado');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->save();

        // Log da atualização
        Log::create([
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'accao' => 'Atualização de Usuário',
            'id_user' => $user->id,
            'descricao' => "Usuário {$user->name} (ID: {$user->id}) atualizado por " . auth()->user()->name,
        ]);

        return redirect()->back()->with('userAtualizado', 'Atualizado');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name; // Armazena o nome antes de deletar
        $user->delete();

        // Log da exclusão
        Log::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'accao' => 'Exclusão de Usuário',
            'id_user' => $id,
            'descricao' => "Usuário {$userName} (ID: {$id}) excluído por " . auth()->user()->name,
        ]);

        return redirect()->back()->with('userRemovido', 'Removido');
    }
}
