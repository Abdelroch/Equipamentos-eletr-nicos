<?php

namespace App\Http\Controllers\Admin\OrderNegotiation;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Services\OrderNegotiationService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Lista todas as encomendas/negociações
     */
    public function index(Request $request)
    {
        $query = OrderNegotiation::join('product', 'product.id', '=', 'order_negotiations.product_id')
            ->join('users', 'users.id', '=', 'order_negotiations.user_id')
            ->select(
                'order_negotiations.*',
                'product.nome as product_name',
                'product.preco as product_original_price',
                'users.name as customer_name',
                'users.email as customer_email'
            )
            ->latest('order_negotiations.created_at');

        if ($request->filled('status')) {
            $query->where('order_negotiations.status', $request->status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Ver comprovativo de pagamento
     */
    public function show_proof($id)
    {
        $order = OrderNegotiation::findOrFail($id);

        if (!$order->payment_proof) {
            return redirect()->back()->with('error', 'Nenhum comprovativo submetido.');
        }

        return response()->file(storage_path('app/public/' . $order->payment_proof));
    }

    /**
     * Aceitar proposta (pending → awaiting_confirmation)
     */
    public function approve(Request $request, $id)
    {
        $order = OrderNegotiation::findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', "Esta encomenda já não está pendente (estado actual: {$order->status}).");
        }

        try {
            OrderNegotiationService::accept($order, auth()->id());

            return redirect()->back()
                ->with('success', "Encomenda #{$id} aceite! Stock reservado por " .
                       OrderNegotiationService::RESERVATION_HOURS . "h.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * NOVO MÉTODO: Confirmar comprovativo (awaiting_confirmation → confirmed)
     */
    public function confirm(Request $request, $id)
    {
        $order = OrderNegotiation::findOrFail($id);

        if ($order->status !== 'awaiting_confirmation') {
            return redirect()->back()
                ->with('error', "Esta encomenda não pode ser confirmada no estado atual: {$order->status}");
        }

        try {
            OrderNegotiationService::confirm($order, auth()->id(), $request->input('admin_notes'));

            return redirect()->back()
                ->with('success', "Encomenda #{$id} confirmada com sucesso! Receita lançada na contabilidade.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Rejeitar encomenda
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $order = OrderNegotiation::findOrFail($id);

        OrderNegotiationService::release($order, 'rejected', auth()->id(), $request->admin_notes);

        return redirect()->back()->with('success', "Encomenda #{$id} rejeitada.");
    }
}
