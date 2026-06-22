<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Supplier;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function list_products(Request $request)
    {
        $query = Product::join('supplier', 'product.id_fornecedor', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor')
            ->orderBy('product.id', 'desc');

        // Filtro de pesquisa
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product.nome', 'like', "%{$search}%")
                    ->orWhere('product.descricao', 'like', "%{$search}%")
                    ->orWhere('product.status', 'like', "%{$search}%")
                    ->orWhere('product.marca', 'like', "%{$search}%"); // ✅ marca pesquisável
            });
        }

        $data['produtos'] = $query->get();
        return view('admin.products.list.index', ['data' => $data]);
    }

    public function negotiate($product_id)
    {
        //
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

    /**
     * Validação comum para store e update.
     */
    protected function validateProduct(Request $request)
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'inform'        => 'required|string',
            'price'         => 'nullable',
            'quantity'      => 'required|integer|min:0',
            'category'      => 'required|string|max:255',
            'supplier_name' => 'required|exists:supplier,id',
            'status'        => 'required',
            'marca'         => 'nullable|string|max:100', // ✅ não obrigatório
            // Cores e tamanhos chegam como string "Prateado, Dourado" do input de texto.
            'cores'         => 'nullable|string|max:500',
            'tamanhos'      => 'nullable|string|max:500',
        ]);
    }

    /**
     * Converte uma string "Prateado, Dourado, Preto" em array limpo
     * ["Prateado","Dourado","Preto"], removendo espaços e entradas vazias.
     * Devolve null se o campo vier vazio, para não gravar [] desnecessário.
     */
    protected function parseOptionsList(?string $raw): ?array
    {
        if (!$raw || trim($raw) === '') {
            return null;
        }

        $items = array_map('trim', explode(',', $raw));
        $items = array_filter($items, fn($v) => $v !== '');
        $items = array_values($items);

        return empty($items) ? null : $items;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        try {
            // Processar cover_image se enviada
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                if ($coverImage->isValid()) {
                    $folder = "imgs/products/temp";
                    $publicPath = public_path($folder);
                    if (!File::exists($publicPath)) {
                        File::makeDirectory($publicPath, 0755, true);
                    }
                    $imageName = time() . '-' . Str::random(10) . '.' . $coverImage->getClientOriginalExtension();
                    $coverImage->move($publicPath, $imageName);
                    $coverImagePath = "$folder/$imageName";
                }
            }

            // Processar imagens adicionais se enviadas
            $imagens = [];
            if ($request->hasFile('imgs')) {
                $folder = "imgs/products/temp";
                $publicPath = public_path($folder);
                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }
                foreach ($request->file('imgs') as $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                        $image->move($publicPath, $imageName);
                        $imagens[] = "$folder/$imageName";
                    }
                }
            }

            $produto = Product::create([
                'nome'                  => $validated['name'],
                'descricao'             => $validated['inform'],
                'preco'                 => $validated['price'],
                'status'                => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria'             => $validated['category'],
                'marca'                 => $validated['marca'] ?? null, // ✅
                'cores'                 => $this->parseOptionsList($validated['cores'] ?? null),
                'tamanhos'              => $this->parseOptionsList($validated['tamanhos'] ?? null),
                'cover_image'           => $coverImagePath,
                'imagens'               => $imagens,
                'id_fornecedor'         => $validated['supplier_name'],
            ]);

            // Mover imagens da pasta temp para a pasta definitiva do produto
            if ($coverImagePath) {
                $newFolder     = "imgs/products/product-{$produto->id}";
                $newPublicPath = public_path($newFolder);
                if (!File::exists($newPublicPath)) {
                    File::makeDirectory($newPublicPath, 0755, true);
                }
                $newCoverPath = $newFolder . '/' . basename($coverImagePath);
                File::move(public_path($coverImagePath), public_path($newCoverPath));

                $newImagens = [];
                foreach ($imagens as $img) {
                    $newImg = $newFolder . '/' . basename($img);
                    File::move(public_path($img), public_path($newImg));
                    $newImagens[] = $newImg;
                }

                $produto->update([
                    'cover_image' => $newCoverPath,
                    'imagens'     => $newImagens,
                ]);
            }

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id'   => $user_id,
                'ip'        => $request->ip(),
                'accao'     => 'Cadastramento',
                'id_user'   => $user_id,
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
    public function update(Request $request, $id)
    {
        $validated = $this->validateProduct($request);

        try {
            $produto = Product::findOrFail($id);

            // Processar imagens adicionais se enviadas (adiciona às existentes)
            $imagens = is_array($produto->imagens) ? $produto->imagens : [];
            if ($request->hasFile('imgs')) {
                $productFolder = "imgs/products/product-{$id}";
                $publicPath    = public_path($productFolder);
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

            // Processar cover_image se enviada (substitui a existente)
            $coverImagePath = $produto->cover_image;
            if ($request->hasFile('cover_image')) {
                $productFolder = "imgs/products/product-{$id}";
                $publicPath    = public_path($productFolder);
                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }
                $coverImage = $request->file('cover_image');
                if ($coverImage->isValid()) {
                    // Apaga a capa anterior se existir
                    if ($coverImagePath && File::exists(public_path($coverImagePath))) {
                        File::delete(public_path($coverImagePath));
                    }
                    $imageName      = time() . '-' . Str::random(10) . '.' . $coverImage->getClientOriginalExtension();
                    $coverImage->move($publicPath, $imageName);
                    $coverImagePath = "$productFolder/$imageName";
                }
            }

            $produto->update([
                'nome'                  => $validated['name'],
                'descricao'             => $validated['inform'],
                'preco'                 => $validated['price'],
                'status'                => $request->status,
                'quantidade_disponivel' => $validated['quantity'],
                'categoria'             => $validated['category'],
                'marca'                 => $validated['marca'] ?? null, // ✅
                'id_fornecedor'         => $validated['supplier_name'],
                'cores'                 => $this->parseOptionsList($validated['cores'] ?? null),
                'tamanhos'              => $this->parseOptionsList($validated['tamanhos'] ?? null),
                'imagens'               => $imagens,
                'estado_venda'          => $request->estado_venda,
                'cover_image'           => $coverImagePath,
            ]);

            // Log
            $user_id = auth()->id();
            Log::create([
                'user_id'   => $user_id,
                'ip'        => $request->ip(),
                'accao'     => 'Atualização',
                'id_user'   => $user_id,
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

            $user_id = auth()->id();
            Log::create([
                'user_id'   => $user_id,
                'ip'        => $request->ip(),
                'accao'     => 'Restauração',
                'id_user'   => $user_id,
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
            $produto     = Product::findOrFail($id);
            $nomeProduto = $produto->nome;

            $produto->delete();

            $user_id = auth()->id();
            Log::create([
                'user_id'   => $user_id,
                'ip'        => $request->ip(),
                'accao'     => 'Eliminação',
                'id_user'   => $user_id,
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
