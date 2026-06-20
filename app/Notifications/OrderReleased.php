<?php

namespace App\Notifications;


use App\Models\OrderNegotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderReleased extends Notification
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
        $mensagens = [
            'rejected'  => "A sua encomenda #{$this->order->id} foi rejeitada." . ($this->order->admin_notes ? " Motivo: {$this->order->admin_notes}" : ''),
            'cancelled' => "A sua encomenda #{$this->order->id} foi cancelada.",
            'expired'   => "O prazo de reserva da sua encomenda #{$this->order->id} expirou. Pode iniciar uma nova negociação ou compra.",
        ];

        return [
            'order_id' => $this->order->id,
            'status'   => $this->order->status,
            'message'  => $mensagens[$this->order->status] ?? "Estado da encomenda #{$this->order->id} actualizado: {$this->order->status}",
        ];
    }
}
