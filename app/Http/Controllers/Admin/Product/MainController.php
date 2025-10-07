<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Supplier;
use Str;

class MainController extends Controller
{
    /**
     * Validação comum para store e update
     */

    public function list_products(Request $request)
    {
        $query = Product::join('supplier', 'product.id_fornecedor', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor')
            ->orderBy('product.id', 'desc');
          //  dd($query);

        // Filtro de pesquisa
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product.nome', 'like', "%{$search}%")
                    ->orWhere('product.descricao', 'like', "%{$search}%")
                    ->orWhere('product.status', 'like', "%{$search}%");
            });
        }

        $data['produtos'] = $query->get();
        //dd($data['produtos']);
        return view('admin.products.list.index', ['data' => $data]);
    }

    public function negotiate($product_id){

    }

    public function list_trashed()
    {
        $data['produtos'] = Product::onlyTrashed()
            ->join('supplier', 'product.id_fornecedor', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor')
            ->orderBy('product.id', 'desc')
            ->get();
        return view('admin.products.trashed.index', ['data' => $data]);
    }

    protected function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'inform' => 'required|string',
            'price' => 'nullable',
            'quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'supplier_name' => 'required|exists:supplier,id',
            'status' => 'required',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        /* dd($request->all()); */
        // dd($request->status);
        try {
            $produto = Product::create([
                'nome' => $validated['name'],
                'descricao' => $validated['inform'],
                'preco' => $validated['price'],
                'status' => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria' => $validated['category'],
                'imagem' => 'default.jpg', 
                'id_fornecedor' => $validated['supplier_name'],
            ]);

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Cadastramento',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} cadastrou o produto {$produto->nome} com ID {$produto->id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('success', 'Produto cadastrado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
   /*  public function update(Request $request, $id)
    {
        $validated = $this->validateProduct($request);

        try {
            $produto = Product::findOrFail($id);

            $produto->update([
                'nome' => $validated['name'],
                'descricao' => $validated['inform'],
                 'preco' => $validated['price'],
                'status' => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria' => $validated['category'],
                'id_fornecedor' => $validated['supplier_name'],
            ]);

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Atualização',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} atualizou o produto {$produto->nome} com ID {$produto->id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('produtoAtualizado', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    } */


    /* public function update(Request $request, $id)
    {
        $validated = $this->validateProduct($request);

        try {
            $produto = Product::findOrFail($id);

            // Processar imagens se enviadas
            $imagens = is_array($produto->imagens) ? $produto->imagens : []; // Inicializa como array vazio se não for array
            if ($request->hasFile('imgs')) {
                $productFolder = "imgs/products/product-{$id}";
                $publicPath = public_path($productFolder);

                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                foreach ($request->file('imgs') as $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                        $image->move($publicPath, $imageName);
                        $imagens[] = "$productFolder/$imageName";
                    }
                }
            }

            $produto->update([
                'nome' => $validated['name'],
                'descricao' => $validated['inform'],
                'preco' => $validated['price'],
                'status' => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria' => $validated['category'],
                'id_fornecedor' => $validated['supplier_name'],
                'imagens' => $imagens, // Salva como array PHP, Laravel converte para JSON
            ]);

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Atualização',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} atualizou o produto {$produto->nome} com ID {$produto->id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    } */

   /*  public function update(Request $request, $id)
{
    $validated = $this->validateProduct($request);

    try {
        $produto = Product::findOrFail($id);

        // Processar imagens se enviadas
        $imagens = is_array($produto->imagens) ? $produto->imagens : [];
        if ($request->hasFile('imgs')) {
            $productFolder = "imgs/products/product-{$id}";
            $publicPath = public_path($productFolder);

            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }

            foreach ($request->file('imgs') as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($publicPath, $imageName);
                    $imagens[] = "$productFolder/$imageName";
                }
            }
        }

        $produto->update([
            'nome' => $validated['name'],
            'descricao' => $validated['inform'],
            'preco' => $validated['price'],
            'status' => $request->status,
            'quantidade_disponivel' => $validated['quantity'],
            'categoria' => $validated['category'],
            'id_fornecedor' => $validated['supplier_name'],
            'imagens' => $imagens,
            'estado_venda' => $request->estado_venda, // Novo campo
        ]);

        // Log
        $user_id = auth()->id();
        Log::create([
            'user_id' => $user_id,
            'ip' => $request->ip(),
            'accao' => 'Atualização',
            'id_user' => $user_id,
            'descricao' => "Usuário {$user_id} atualizou o produto {$produto->nome} com ID {$produto->id}.",
        ]);

        return redirect()->route('admin.gestao.produtos')
            ->with('success', 'Produto atualizado com sucesso!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
    }
} */
public function update(Request $request, $id)
    {
        $validated = $this->validateProduct($request);

        try {
            $produto = Product::findOrFail($id);

            // Processar imagens se enviadas
            $imagens = is_array($produto->imagens) ? $produto->imagens : [];
            if ($request->hasFile('imgs')) {
                $productFolder = "imgs/products/product-{$id}";
                $publicPath = public_path($productFolder);

                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                foreach ($request->file('imgs') as $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                        $image->move($publicPath, $imageName);
                        $imagens[] = "$productFolder/$imageName";
                    }
                }
            }

            // Processar cover_image se enviada
            $coverImagePath = $produto->cover_image; // Manter a imagem existente por padrão
            if ($request->hasFile('cover_image')) {
                $productFolder = "imgs/products/product-{$id}";
                $publicPath = public_path($productFolder);

                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                $coverImage = $request->file('cover_image');
                if ($coverImage->isValid()) {
                    // Excluir a imagem de capa anterior, se existir
                    if ($coverImagePath && File::exists(public_path($coverImagePath))) {
                        File::delete(public_path($coverImagePath));
                    }

                    $imageName = time() . '-' . Str::random(10) . '.' . $coverImage->getClientOriginalExtension();
                    $coverImage->move($publicPath, $imageName);
                    $coverImagePath = "$productFolder/$imageName";
                }
            }

            $produto->update([
                'nome' => $validated['name'],
                'descricao' => $validated['inform'],
                'preco' => $validated['price'],
                'status' => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria' => $validated['category'],
                'id_fornecedor' => $validated['supplier_name'],
                'imagens' => $imagens,
                'estado_venda' => $request->estado_venda,
                'cover_image' => $coverImagePath, // Novo campo
            ]);

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Atualização',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} atualizou o produto {$produto->nome} com ID {$produto->id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }


    public function restore(Request $request, $id)
    {
        try {
            $produto = Product::withTrashed()->findOrFail($id);
            $produto->restore();

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Restauração',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} restaurou o produto {$produto->nome} com ID {$id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('success', 'Produto restaurado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao restaurar produto: ' . $e->getMessage()]);
        }
    }
    public function destroy(Request $request, $id)
    {
        try {
            $produto = Product::findOrFail($id);
            $nomeProduto = $produto->nome;

            $produto->delete();

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Eliminação',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} eliminou o produto {$nomeProduto} com ID {$id}.",
            ]);

            return redirect()->route('admin.gestao.produtos')
                ->with('success', 'Produto removido com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao remover produto: ' . $e->getMessage()]);
        }
    }
}
