<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class MainController extends Controller
{


    public function store_order_negotiation(Request $request)
    {
        // Validar os dados do formulário
        $validated = $request->validate([
            'offer' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:product,id',
        ]);

        // Obter o usuário autenticado
        $userId = Auth::id();

        // Obter o produto para pegar o original_price e delivery_cost
        $product = DB::table('product')->where('id', $validated['product_id'])->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $originalPrice = $product->preco; // Ajuste o nome da coluna de preço no modelo Product
        $deliveryCost = $product->delivery_cost ?? 0; // Ajuste se delivery_cost estiver na tabela products

        // Calcular o total_price
        $proposedPrice = $validated['offer'];
        $totalPrice = $proposedPrice + $deliveryCost;

        // Iniciar transação para garantir consistência
        DB::beginTransaction();

        try {
            // Criar o registro na tabela order_negotiations
            OrderNegotiation::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'quantity' => 1, // Valor padrão conforme a migração
                'original_price' => $originalPrice,
                'proposed_price' => $proposedPrice,
                'delivery_cost' => $deliveryCost,
                'total_price' => $totalPrice,
                'delivery_location' => $product->delivery_location ?? 'Luanda', // Ajuste conforme necessário
                'notes' => $validated['notes'],
                'payment_method' => null, // Pode ser preenchido depois
                'status' => 'pending',
            ]);

            // Confirmar transação
            DB::commit();

            return redirect()->back()->with('success', 'Negociação de preço solicitada com sucesso!');
        } catch (\Exception $e) {
            // Reverter transação em caso de erro
            DB::rollBack();

            dd($e->getMessage());

            return redirect()->back()->with('error', 'Ocorreu um erro ao processar a negociação. Tente novamente.');
        }
    }

    public function order_requests(){
        $userAuthed = auth()->user();
        $data['order_requests'] = OrderNegotiation::join('product','product.id','order_negotiations.product_id')
            ->select('order_negotiations.*','product.nome as product_name')
            ->where('user_id', $userAuthed->id)->get();
        return view('customer.order-requests', $data);
    }

    public function profile()
    {
        $userAuthed = auth()->user();
        $data['userAuthed'] = User::join('customer','customer.user_id','users.id')
            ->where('users.id',$userAuthed->id)
            ->select('users.*', 'customer.*')
            ->first();
        return view('customer.profile', $data);
    }

}
