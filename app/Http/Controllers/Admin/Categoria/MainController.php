<?php

namespace App\Http\Controllers\Admin\Categoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Product;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('admin.categorias.index', ['data' => ['categorias' => $categorias]]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'  => 'required|string|max:255|unique:categorias,nome',
            'icone' => 'nullable|string|max:100',
        ]);

        $categoria = Categoria::create([
            'nome'   => $validated['nome'],
            'slug'   => Str::slug($validated['nome']),
            'icone'  => $validated['icone'] ?? 'fa fa-cube',
            'activa' => $request->boolean('activa', true),
        ]);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Criação de Categoria',
            'descricao' => "Categoria {$categoria->nome} criada por " . auth()->user()->name,
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nome'  => 'required|string|max:255|unique:categorias,nome,' . $id,
            'icone' => 'nullable|string|max:100',
        ]);

        $categoria->update([
            'nome'   => $validated['nome'],
            'slug'   => Str::slug($validated['nome']),
            'icone'  => $validated['icone'] ?? $categoria->icone,
            'activa' => $request->boolean('activa', true),
        ]);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Atualização de Categoria',
            'descricao' => "Categoria {$categoria->nome} atualizada por " . auth()->user()->name,
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria atualizada.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        if (Product::where('categoria', $categoria->slug)->exists()) {
            return redirect()->back()->with('error', 'Não é possível remover: existem produtos associados a esta categoria.');
        }

        $nome = $categoria->nome;
        $categoria->delete();

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => request()->ip(),
            'accao'     => 'Exclusão de Categoria',
            'descricao' => "Categoria {$nome} removida por " . auth()->user()->name,
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria removida.');
    }
}
