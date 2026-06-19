<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderNegotiationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    /**
     * Registra uma nova negociação de preço.
     * Estado inicial: 'pending'. NÃO mexe em stock — isso só acontece
     * quando o admin aceitar (OrderNegotiationService::accept).
     */
    public function store_order_negotiation(Request $request)
    {
        $validated = $request->validate([
            'offer'      => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
            'product_id' => 'required|exists:product,id',
        ]);

        $product = Product::where('id', $validated['product_id'])->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        // Validação de stock que faltava: sem isto, um produto esgotado
        // continuava a aceitar negociações infinitas.
        if ($product->quantidade_disponivel < 1 || $product->estado_venda !== 'disponivel') {
            return redirect()->back()->with('error', 'Produto sem stock disponível para negociação.');
        }

        $originalPrice = (float) $product->preco;
        $proposedPrice = (float) $validated['offer'];
        $deliveryCost  = 0;
        $totalPrice    = $proposedPrice + $deliveryCost;

        $customerData = DB::table('customer')
            ->where('user_id', Auth::id())
            ->first(['address', 'bairro', 'province', 'reference_point', 'payment_method']);

        try {
            OrderNegotiationService::create([
                'user_id'            => Auth::id(),
                'product_id'         => $validated['product_id'],
                'quantity'           => 1,
                'original_price'     => $originalPrice,
                'proposed_price'     => $proposedPrice,
                'delivery_cost'      => $deliveryCost,
                'delivery_address'   => $customerData->address ?? null,
                'delivery_bairro'    => $customerData->bairro ?? null,
                'delivery_reference' => $customerData->reference_point ?? null,
                'delivery_location'  => $customerData->province ?? 'Luanda',
                'total_price'        => $totalPrice,
                'payment_method'     => $customerData->payment_method ?? 'Não definido',
                'notes'              => $validated['notes'],
                'status'             => 'pending',
            ]);

            return redirect()->back()
                ->with('success', 'Negociação enviada com sucesso! Aguarde contato.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar a negociação. Tente novamente.');
        }
    }

    /**
     * Compra directa (sem negociação — usa o preço original do produto).
     * Estado inicial: 'pending', igual à negociação — a reserva de stock
     * só acontece quando o admin aceitar, mantendo o fluxo consistente
     * e evitando dois caminhos diferentes de gestão de stock.
     */
    public function store_direct_purchase(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $product = Product::where('id', $validated['product_id'])->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        if ($product->quantidade_disponivel < 1 || $product->estado_venda !== 'disponivel') {
            return redirect()->back()->with('error', 'Produto sem stock disponível para compra.');
        }

        $originalPrice = (float) $product->preco;
        $deliveryCost  = 2500;
        $totalPrice    = $originalPrice + $deliveryCost;

        $customerData = DB::table('customer')
            ->where('user_id', Auth::id())
            ->first(['address', 'bairro', 'province', 'reference_point']);

        try {
            OrderNegotiationService::create([
                'user_id'            => Auth::id(),
                'product_id'         => $validated['product_id'],
                'quantity'           => 1,
                'original_price'     => $originalPrice,
                'proposed_price'     => $originalPrice,
                'delivery_cost'      => $deliveryCost,
                'delivery_address'   => $customerData->address ?? null,
                'delivery_bairro'    => $customerData->bairro ?? null,
                'delivery_reference' => $customerData->reference_point ?? null,
                'delivery_location'  => $customerData->province ?? 'Luanda',
                'total_price'        => $totalPrice,
                'notes'              => $validated['notes'] ?? null,
                // 'accepted' directamente já não seria seguro aqui: queremos
                // que o admin sempre confirme antes de reservar stock.
                // Se quiseres compra directa = auto-aceite, troca para 'pending'
                // e usa um job/observer a chamar accept() automaticamente.
                'status'             => 'pending',
            ]);

            return redirect()->back()
                ->with('success', 'Pedido de compra enviado! Aguarde aprovação para prosseguir com o pagamento.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar a compra. Tente novamente.');
        }
    }

    public function customer_create_account(Request $request)
    {
        $validated = $request->validate([
            'nif'          => 'required|string|size:14|unique:customer,nif',
            'name'         => 'required|string|max:255',
            'birth_date'   => 'nullable|date',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:30|unique:customer,phone_number',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
                'access_level' => 'customer',
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

    /**
     * Submeter comprovativo de pagamento.
     * Aceita tanto 'accepted' (fluxo normal) — já não bloqueia incorrectamente.
     */
    public function submit_payment_proof(Request $request, $order_id)
    {
        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = OrderNegotiation::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'accepted') {
            return redirect()->back()
                ->with('error', 'Não é possível submeter comprovativo para esta encomenda.');
        }

        // Protege contra submissão de comprovativo depois da reserva ter expirado
        // (o cliente pode estar a enviar no exacto momento em que o job corre).
        if ($order->reserved_until !== null && $order->reserved_until->isPast()) {
            return redirect()->back()
                ->with('error', 'O prazo de reserva desta encomenda expirou. Por favor inicie uma nova negociação/compra.');
        }

        $path = $request->file('payment_proof')
            ->store('payment_proofs', 'public');

        $order->update([
            'payment_proof'      => $path,
            'proof_submitted_at' => now(),
            'status'             => 'awaiting_confirmation',
        ]);

        return redirect()->back()
            ->with('success', 'Comprovativo enviado! Aguarda confirmação.');
    }

    public function update_account(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:30',
            'password'     => 'nullable|string',
            'new_password' => 'nullable|string|min:8|same:password_confirm',
        ]);

        if ($request->filled('new_password')) {
            if (!$request->filled('password') || !Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('error', 'Senha actual incorrecta.');
            }
            $user->password = Hash::make($validated['new_password']);
        }

        if ($validated['email'] !== $user->email) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        $user->save();

        DB::table('customer')
            ->where('user_id', $user->id)
            ->update([
                'phone_number' => $validated['phone_number'] ?? null,
                'updated_at'   => now(),
            ]);

        return redirect()->back()->with('success', 'Dados actualizados com sucesso.');
    }
}
