<?php

namespace App\Http\Controllers\Admin\OrderNegotiation;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Services\OrderNegotiationService;
use App\Notifications\OrderNegotiationRejected;
use Illuminate\Http\RedirectResponse;
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
                'product.preco as original_price',
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
    public function show_proof(int $id)
    {
        $order = OrderNegotiation::findOrFail($id);

        if (! $order->payment_proof) {
            return redirect()->back()->with('error', 'Nenhum comprovativo submetido para esta encomenda.');
        }

        return response()->file(storage_path('app/public/' . $order->payment_proof));
    }

    /**
     * Aceitar proposta (pending → awaiting_confirmation)
     * O admin aceita a proposta do cliente; o cliente passa a enviar comprovativo.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $order = OrderNegotiation::findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', "Esta encomenda já não está pendente (estado actual: {$order->status}).");
        }

        try {
            OrderNegotiationService::accept($order, auth()->id());

            return redirect()->back()
                ->with('success', "Proposta #{$id} aceite! O cliente foi notificado para enviar o comprovativo.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * ✅ NOVO: Rejeitar proposta pendente (pending → rejected)
     * Usado quando a oferta do cliente não faz sentido comercial.
     * Diferente de reject() que rejeita o comprovativo (awaiting_confirmation → rejected).
     */
    public function reject_negotiation(Request $request, int $id): RedirectResponse
    {
        $request->validate(
            [
                'admin_notes' => ['nullable', 'string', 'max:500'],
            ],
            [
                'admin_notes.max' => 'O motivo não pode ultrapassar 500 caracteres.',
            ]
        );

        $order = OrderNegotiation::findOrFail($id);

        // Só propostas pendentes podem ser rejeitadas por esta via
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', "Apenas propostas pendentes podem ser rejeitadas. Estado actual: {$order->status}.");
        }

        $order->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes ?? 'Proposta rejeitada pelo vendedor.',
            'rejected_at' => now(),
        ]);

        // Notifica o cliente
        $notifiable = $order->user ?? optional($order->customer)->user;
        if ($notifiable) {
            $notifiable->notify(new OrderNegotiationRejected($order));
        }

        return redirect()->back()
            ->with('success', "Proposta #{$id} rejeitada. O cliente foi notificado.");
    }

    /**
     * Confirmar comprovativo (awaiting_confirmation → confirmed)
     */
    public function confirm(Request $request, int $id): RedirectResponse
    {
        $order = OrderNegotiation::findOrFail($id);

        if ($order->status !== 'awaiting_confirmation') {
            return redirect()->back()
                ->with('error', "Esta encomenda não pode ser confirmada no estado actual: {$order->status}.");
        }

        try {
            OrderNegotiationService::confirm($order, auth()->id(), $request->input('admin_notes'));

            return redirect()->back()
                ->with('success', "Encomenda #{$id} confirmada com sucesso! Venda registada.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Rejeitar comprovativo (awaiting_confirmation → rejected)
     * Usado quando o comprovativo enviado é inválido ou o valor não corresponde.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate(
            [
                'admin_notes' => ['required', 'string', 'max:500'],
            ],
            [
                'admin_notes.required' => 'O motivo da rejeição é obrigatório.',
                'admin_notes.max'      => 'O motivo não pode ultrapassar 500 caracteres.',
            ]
        );

        $order = OrderNegotiation::findOrFail($id);

        OrderNegotiationService::release($order, 'rejected', auth()->id(), $request->admin_notes);

        return redirect()->back()
            ->with('success', "Encomenda #{$id} rejeitada.");
    }
}
