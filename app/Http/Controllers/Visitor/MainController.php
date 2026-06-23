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
    /**
     * Aplica os filtros de pesquisa, categoria e marca (usado em múltiplas views)
     */
    private function applyFilters($query, Request $request, $categorias)
    {
        $termoPesquisa    = null;
        $categoriaActiva  = null;
        $marcaActiva      = null;
        $marcaInexistente = false;

        // Filtro de texto — barra de pesquisa (?q=...)
        if ($request->filled('q')) {
            $termoPesquisa = trim($request->q);
            $query->where(function ($q) use ($termoPesquisa) {
                $q->where('nome', 'like', "%{$termoPesquisa}%")
                    ->orWhere('descricao', 'like', "%{$termoPesquisa}%")
                    ->orWhere('marca', 'like', "%{$termoPesquisa}%")
                    ->orWhere('id', is_numeric($termoPesquisa) ? (int)$termoPesquisa : -1);
            });
        }

        // Filtro de categoria (?categoria=...)
        if ($request->filled('categoria') && $request->categoria !== 'all') {
            $categoriaActiva = $categorias->firstWhere('slug', $request->categoria);
            if ($categoriaActiva) {
                $query->where(function ($q) use ($categoriaActiva) {
                    $q->where('categoria', $categoriaActiva->slug)
                        ->orWhere('categoria', $categoriaActiva->nome);
                });
            }
        }

        // Filtro de marca (?marca=...)
        if ($request->filled('marca')) {
            $marcaActiva = strtolower(trim($request->marca));
            $marcaExiste = Product::whereRaw('LOWER(`marca`) = ?', [$marcaActiva])->exists();

            if (!$marcaExiste) {
                $marcaInexistente = true;
            } else {
                $query->whereRaw('LOWER(`marca`) = ?', [$marcaActiva]);
            }
        }

        return [
            'query'            => $query,
            'termoPesquisa'    => $termoPesquisa,
            'categoriaActiva'  => $categoriaActiva,
            'marcaActiva'      => $marcaActiva,
            'marcaInexistente' => $marcaInexistente,
        ];
    }

    public function index(Request $request)
    {
        $categorias = Categoria::where('activa', true)->orderBy('nome')->get();

        $filterData = $this->applyFilters(
            Product::whereIn('estado_venda', ['disponivel'])
                   ->whereIn('status', ['Extremamente Bom', 'Bom']),
            $request,
            $categorias
        );

        $query = $filterData['query'];

        $data = [
            'categorias'       => $categorias,
            'termoPesquisa'    => $filterData['termoPesquisa'],
            'categoriaActiva'  => $filterData['categoriaActiva'],
            'marcaActiva'      => $filterData['marcaActiva'],
            'marcaInexistente' => $filterData['marcaInexistente'],
        ];

        if ($filterData['marcaInexistente']) {
            $data['products_featured']  = collect();
            $data['products_good']      = collect();
            $data['products_very_good'] = collect();
            $data['products_monitors']  = collect();
            $data['products_sold']      = collect();
            $data['products_carcass']   = collect();
        } else {
            $data['products_featured']  = (clone $query)->orderByDesc('created_at')->take(10)->get();
            $data['products_good']      = (clone $query)->where('status', 'Bom')->get();
            $data['products_very_good'] = (clone $query)
                ->where('status', 'Extremamente Bom')
                ->where('categoria', 'laptops')
                ->get();
            $data['products_monitors']  = (clone $query)
                ->where('status', 'Extremamente Bom')
                ->where('categoria', 'monitors')
                ->get();
            $data['products_sold']      = (clone $query)->where('estado_venda', 'vendido')->get();
            $data['products_carcass']   = (clone $query)->where('status', 'Irreparável')->get();
        }

        return view('index', $data);
    }

    public function product_details(Request $request, $product_slug)
    {
        $categorias = Categoria::where('activa', true)->orderBy('nome')->get();

        $filterData = $this->applyFilters(Product::query(), $request, $categorias);

        $data = [
            'product'          => Product::where('slug', $product_slug)->firstOrFail(),
            'categorias'       => $categorias,
            'termoPesquisa'    => $filterData['termoPesquisa'],
            'categoriaActiva'  => $filterData['categoriaActiva'],
            'marcaActiva'      => $filterData['marcaActiva'],
            'marcaInexistente' => $filterData['marcaInexistente'],
        ];

        return view('visitor.product-details', $data);
    }

    public function store(Request $request)
    {
        $categorias = Categoria::where('activa', true)->orderBy('nome')->get();

        $baseQuery = Product::whereIn('estado_venda', ['disponivel'])
            ->whereIn('status', ['Extremamente Bom', 'Bom']);

        $filterData = $this->applyFilters($baseQuery, $request, $categorias);

        if ($filterData['marcaInexistente']) {
            $products       = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $totalSemFiltro = 0;
        } else {
            $products = $filterData['query']
                ->orderByDesc('created_at')
                ->paginate(12)
                ->appends($request->query());

            $totalSemFiltro = ($filterData['termoPesquisa'] || $filterData['categoriaActiva'] || $filterData['marcaActiva'])
                ? Product::whereIn('estado_venda', ['disponivel'])
                    ->whereIn('status', ['Extremamente Bom', 'Bom'])
                    ->count()
                : null;
        }

        return view('visitor.store', [
            'categorias'         => $categorias,
            'products_very_good' => $products,
            'termoPesquisa'      => $filterData['termoPesquisa'],
            'categoriaActiva'    => $filterData['categoriaActiva'],
            'marcaActiva'        => $filterData['marcaActiva'],
            'marcaInexistente'   => $filterData['marcaInexistente'],
            'totalSemFiltro'     => $totalSemFiltro,
        ]);
    }

    /**
     * Cria conta de cliente a partir do formulário da homepage.
     *
     * NIF aceita qualquer 14 caracteres alfanuméricos (letras + números).
     * Exemplos válidos: 003519344LA042 | 12345678000090
     */
    public function customer_create_account(Request $request)
    {
        $request->validate([
            // NIF: 14 caracteres alfanuméricos — aceita formato angolano com letras (003519344LA042)
            // ou formato 100% numérico (12345678000090)
            'nif'          => ['required', 'string', 'regex:/^[A-Za-z0-9]{14}$/', 'unique:customer,nif'],
            'name'         => ['required', 'string', 'min:3', 'max:255'],
            'birth_date'   => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            // Telefone angolano: 9 dígitos começando por 9 (ex: 923456789)
            'phone_number' => ['required', 'string', 'regex:/^9[0-9]{8}$/', 'unique:customer,phone_number'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // --- NIF / B.I. ---
            'nif.required'          => 'O número do B.I. é obrigatório.',
            'nif.regex'             => 'O B.I. deve ter exactamente 14 caracteres alfanuméricos (ex: 003519344LA042 ou 12345678000090).',
            'nif.unique'            => 'Este número de B.I. já está registado.',

            // --- Nome ---
            'name.required'         => 'O nome completo é obrigatório.',
            'name.min'              => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max'              => 'O nome não pode ter mais de 255 caracteres.',

            // --- Data de nascimento ---
            'birth_date.required'   => 'A data de nascimento é obrigatória.',
            'birth_date.date'       => 'A data de nascimento introduzida não é válida.',
            'birth_date.before'     => 'A data de nascimento deve ser anterior à data de hoje.',
            'birth_date.after'      => 'A data de nascimento não parece ser válida.',

            // --- Email ---
            'email.required'        => 'O endereço de e-mail é obrigatório.',
            'email.email'           => 'Introduza um endereço de e-mail válido (ex: nome@dominio.com).',
            'email.unique'          => 'Já existe uma conta com este e-mail. Por favor faça login.',

            // --- Telefone ---
            'phone_number.required' => 'O número de telefone é obrigatório.',
            'phone_number.regex'    => 'Telefone inválido. Use 9 dígitos começando por 9 (ex: 923456789).',
            'phone_number.unique'   => 'Este número de telefone já está registado.',

            // --- Senha ---
            'password.required'     => 'A senha é obrigatória.',
            'password.min'          => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'    => 'A confirmação da senha não corresponde. Verifique e tente novamente.',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'access_level' => 'customer',
            ]);

            Customer::create([
                'user_id'      => $user->id,
                'nif'          => strtoupper($request->nif),
                'birth_date'   => $request->birth_date,
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();

            // Usa 'status' para o componente x-auth-session-status da página de login mostrar
            return redirect()->route('login')
                ->with('status', 'Conta criada com sucesso! Faça login para continuar.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao criar conta de cliente', [
                'message' => $e->getMessage(),
                'email'   => $request->email ?? null,
            ]);

            return back()->withInput()->withErrors([
                'email' => 'Ocorreu um erro interno ao criar a conta. Tente novamente.',
            ]);
        }
    }
}
