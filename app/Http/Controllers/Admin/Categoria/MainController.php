<?php

namespace App\Http\Controllers\Admin\Categoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'   => 'required|string|max:100|unique:categorias,nome',
            'slug'   => 'required|string|max:100|unique:categorias,slug',
            'icone'  => 'nullable|string|max:50',
            'activa' => 'required|boolean',
        ]);

        $cat = Categoria::create($validated);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Criação de Categoria',
            'descricao' => "Categoria {$cat->nome} criada.",
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', "Categoria '{$cat->nome}' criada com sucesso!");
    }

    public function update(Request $request, $id)
    {
        $cat = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nome'   => 'required|string|max:100|unique:categorias,nome,' . $id,
            'slug'   => 'required|string|max:100|unique:categorias,slug,' . $id,
            'icone'  => 'nullable|string|max:50',
            'activa' => 'required|boolean',
        ]);

        $cat->update($validated);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Actualização de Categoria',
            'descricao' => "Categoria {$cat->nome} actualizada.",
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', "Categoria '{$cat->nome}' actualizada!");
    }

    public function destroy(Request $request, $id)
    {
        $cat = Categoria::findOrFail($id);
        $nome = $cat->nome;
        $cat->delete();

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Eliminação de Categoria',
            'descricao' => "Categoria {$nome} eliminada.",
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', "Categoria '{$nome}' eliminada.");
    }
}
