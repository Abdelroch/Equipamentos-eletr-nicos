<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    /**
     * Registra uma nova negociação de preço
     */
    /**
     * Registra uma nova negociação de preço
     */
    public function store_order_negotiation(Request $request)
    {
        $validated = $request->validate([
            'offer'       => 'required|numeric|min:0',
            'notes'       => 'nullable|string|max:1000',
            'product_id'  => 'required|exists:product,id',
        ]);

        $userId = Auth::id();

        // Buscar apenas as colunas que realmente existem na tabela
        $product = DB::table('product')
            ->where('id', $validated['product_id'])
            ->first(['preco', 'nome']);   // ← Apenas colunas existentes

        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $originalPrice   = (float) $product->preco;
        $proposedPrice   = (float) $validated['offer'];
        $deliveryCost    = 0;                    // Valor padrão (sem delivery_cost na tabela)
        $totalPrice      = $proposedPrice + $deliveryCost;

        DB::beginTransaction();

        try {
            OrderNegotiation::create([
                'user_id'           => $userId,
                'product_id'        => $validated['product_id'],
                'quantity'          => 1,
                'original_price'    => $originalPrice,
                'proposed_price'    => $proposedPrice,
                'delivery_cost'     => $deliveryCost,
                'total_price'       => $totalPrice,
                'delivery_location' => 'Luanda',           // Valor padrão
                'notes'             => $validated['notes'],
                'status'            => 'pending',
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Negociação enviada com sucesso! Aguarde contato.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao processar a negociação. Tente novamente.');
        }
    }
    /**
     * Compra directa (sem negociação — usa o preço original do produto)
     */
    public function store_direct_purchase(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $product = DB::table('product')
            ->where('id', $validated['product_id'])
            ->first(['preco', 'nome']);

        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $originalPrice = (float) $product->preco;
        $deliveryCost  = 2500;
        $totalPrice    = $originalPrice + $deliveryCost;

        DB::beginTransaction();
        try {
            OrderNegotiation::create([
                'user_id'           => Auth::id(),
                'product_id'        => $validated['product_id'],
                'quantity'          => 1,
                'original_price'    => $originalPrice,
                'proposed_price'    => $originalPrice, // preço aceite sem negociação
                'delivery_cost'     => $deliveryCost,
                'total_price'       => $totalPrice,
                'delivery_location' => 'Luanda',
                'notes'             => $validated['notes'] ?? null,
                'status'            => 'accepted', // compra directa → já aceite
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Compra realizada com sucesso! Aguarde contacto para entrega.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao processar a compra. Tente novamente.');
        }
    }
    public function customer_create_account(Request $request)
    {
        $validated = $request->validate([
            'nif'                  => 'required|string|size:14|unique:customer,nif',
            'name'                 => 'required|string|max:255',
            'birth_date'           => 'nullable|date',
            'email'                => 'required|email|unique:users,email',
            'phone_number'         => 'required|string|max:30|unique:customer,phone_number',
            'password'             => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            DB::table('customer')->insert([
                'user_id'      => $user->id,
                'nif'          => $validated['nif'],
                'phone_number' => $validated['phone_number'],
                'birth_date'   => $validated['birth_date'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('index')
                ->with('success', 'Conta criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar conta. Tente novamente.');
        }
    }

    /**
     * Lista todas as solicitações de negociação do usuário
     */
    public function order_requests()
    {
        $requests = OrderNegotiation::join('product', 'product.id', '=', 'order_negotiations.product_id')
            ->select(
                'order_negotiations.*',
                'product.nome as product_name',
                'product.preco as product_original_price'
            )
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.order-requests', [
            'order_requests' => $requests
        ]);
    }

    /**
     * Perfil do cliente
     */
    public function profile()
    {
        $user = User::join('customer', 'customer.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->select(
                'users.*',
                'customer.*',
                'users.name as user_name',
                'users.email'
            )
            ->first();

        if (!$user) {
            abort(404, 'Perfil não encontrado');
        }

        return view('customer.profile', [
            'userAuthed' => $user
        ]);
    }
}
