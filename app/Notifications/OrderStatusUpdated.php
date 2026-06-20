<?php

namespace App\Notifications;

use App\Models\OrderNegotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public OrderNegotiation $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $labels = [
            'pending'                => 'Aguarda resposta',
            'accepted'                => 'Aceite — falta pagamento',
            'awaiting_confirmation'   => 'Comprovativo enviado',
            'confirmed'               => 'Confirmada',
            'rejected'                => 'Rejeitada',
        ];

        return [
            'order_id'      => $this->order->id,
            'product_name'  => $this->order->product->nome ?? 'Produto',
            'status'        => $this->order->status,
            'message'       => 'A sua encomenda #' . $this->order->id . ' está agora: ' . ($labels[$this->order->status] ?? $this->order->status),
        ];
    }
}
