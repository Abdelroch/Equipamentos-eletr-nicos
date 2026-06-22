<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MainController extends Controller
{
    public function index()
    {
        $data['products_featured']  = Product::orderByDesc('created_at')->take(10)->get();
        $data['products_good']      = Product::orderByDesc('created_at')->where('status', 'Bom')->get();
        $data['products_very_good'] = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->where('categoria', 'laptops')->get();
        $data['products_monitors']  = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->where('categoria', 'monitors')->get();
        $data['products_sold']      = Product::orderByDesc('created_at')->where('estado_venda', 'vendido')->get();
        $data['products_carcass']   = Product::orderByDesc('created_at')->where('status', 'Irreparável')->get();
        $data['categorias']         = Categoria::where('activa', true)->orderBy('nome')->get();

        return view('index', $data);
    }

    public function product_details($product_slug)
    {
        $data['product'] = Product::where('slug', $product_slug)->firstOrFail();
        return view('visitor.product-details', $data);
    }

    public function store(Request $request)
    {
        $categorias = Categoria::where('activa', true)->orderBy('nome')->get();

        $query = Product::whereIn('estado_venda', ['disponivel'])
            ->whereIn('status', ['Extremamente Bom', 'Bom']);

        // -----------------------------------------------------------------------
        // Filtro de texto — barra de pesquisa do header (?q=...)
        // Pesquisa em nome, descricao, marca e id.
        // -----------------------------------------------------------------------
        $termoPesquisa = null;
        if ($request->filled('q')) {
            $termoPesquisa = trim($request->q);
            $query->where(function ($q) use ($termoPesquisa) {
                $q->where('nome', 'like', "%{$termoPesquisa}%")
                    ->orWhere('descricao', 'like', "%{$termoPesquisa}%")
                    ->orWhere('marca', 'like', "%{$termoPesquisa}%")
                    ->orWhere('id', is_numeric($termoPesquisa) ? (int)$termoPesquisa : -1);
            });
        }

        // -----------------------------------------------------------------------
        // Filtro de categoria — <select name="categoria"> do header
        // Tenta pelo slug e depois pelo nome, para cobrir inconsistências em
        // como o valor foi gravado em product.categoria.
        // -----------------------------------------------------------------------
        $categoriaActiva = null;
        if ($request->filled('categoria') && $request->categoria !== 'all') {
            $categoriaActiva = $categorias->firstWhere('slug', $request->categoria);
            if ($categoriaActiva) {
                $query->where(function ($q) use ($categoriaActiva) {
                    $q->where('categoria', $categoriaActiva->slug)
                        ->orWhere('categoria', $categoriaActiva->nome);
                });
            }
        }

        // -----------------------------------------------------------------------
        // Filtro de marca — carousel "Navegue por Marca" (?marca=jbl)
        // Case-insensitive: ?marca=JBL e ?marca=jbl dão o mesmo resultado.
        // -----------------------------------------------------------------------
        $marcaActiva      = null;
        $marcaInexistente = false;

        if ($request->filled('marca')) {
            $marcaActiva = strtolower(trim($request->marca));
            $marcaExiste = Product::whereRaw('LOWER(`marca`) = ?', [$marcaActiva])->exists();

            if (!$marcaExiste) {
                $marcaInexistente = true;
            } else {
                $query->whereRaw('LOWER(`marca`) = ?', [$marcaActiva]);
            }
        }

        if ($marcaInexistente) {
    $products       = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
    $totalSemFiltro = 0;
} else {
    $products = $query->orderByDesc('created_at')
        ->paginate(12)
        ->appends($request->query());

            $totalSemFiltro = ($termoPesquisa || $categoriaActiva || $marcaActiva)
                ? Product::whereIn('estado_venda', ['disponivel'])
                ->whereIn('status', ['Extremamente Bom', 'Bom'])
                ->count()
                : null;
        }

        return view('visitor.store', [
            'categorias'         => $categorias,
            'products_very_good' => $products,
            'termoPesquisa'      => $termoPesquisa,
            'categoriaActiva'    => $categoriaActiva,
            'marcaActiva'        => $marcaActiva,
            'marcaInexistente'   => $marcaInexistente,
            'totalSemFiltro'     => $totalSemFiltro,
        ]);
    }

    public function customer_create_account(Request $request)
    {
        $validated = $request->validate([
            'nif'          => 'required|string|size:14|regex:/^\d{9}[A-Za-z]{2}\d{3}$/|unique:customer,nif',
            'name'         => 'required|string|max:255',
            'birth_date'   => 'required|date|before:today',
            'email'        => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:customer,phone_number',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'nif.regex' => 'NIF deve ter o formato: 9 dígitos + 2 letras + 3 dígitos (ex: 003519344HA042).',
        ]);

        $validated['nif'] = strtoupper($validated['nif']);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']),
                'access_level' => 'customer',
            ]);

            Customer::create([
                'user_id'      => $user->id,
                'nif'          => $validated['nif'],
                'birth_date'   => $validated['birth_date'],
                'phone_number' => $validated['phone_number'],
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Conta criada com sucesso! Faça login.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao criar conta de cliente', [
                'message' => $e->getMessage(),
                'email'   => $validated['email'] ?? null,
            ]);

            throw ValidationException::withMessages([
                'email' => 'Ocorreu um erro ao criar a conta. Tente novamente.',
            ]);
        }
    }
}
