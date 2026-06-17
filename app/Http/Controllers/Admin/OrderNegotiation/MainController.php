<?php

namespace App\Http\Controllers\Admin\OrderNegotiation;

use App\Http\Controllers\Controller;
use App\Models\OrderNegotiation;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * Aprovar encomenda
     */
    public function approve(Request $request, $id)
    {
        $order = OrderNegotiation::findOrFail($id);

        $order->update([
            'status'      => 'confirmed',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Aprovação de Encomenda',
            'descricao' => "Encomenda #{$id} aprovada por " . auth()->user()->name,
        ]);

        return redirect()->back()->with('success', "Encomenda #{$id} aprovada!");
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

        $order->update([
            'status'      => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        Log::create([
            'user_id'   => auth()->id(),
            'ip'        => $request->ip(),
            'accao'     => 'Rejeição de Encomenda',
            'descricao' => "Encomenda #{$id} rejeitada. Motivo: {$request->admin_notes}",
        ]);

        return redirect()->back()->with('success', "Encomenda #{$id} rejeitada.");
    }
}
