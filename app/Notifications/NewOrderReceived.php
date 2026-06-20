<?php

namespace App\Notifications;

use App\Models\OrderNegotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
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
        $tipo = $this->order->original_price == $this->order->proposed_price
            ? 'Compra directa'
            : 'Proposta de negociação';

        return [
            'order_id'       => $this->order->id,
            'status'         => $this->order->status,
            'customer_name'  => $this->order->user->name ?? 'Cliente',
            'message'        => "{$tipo} recebida (Encomenda #{$this->order->id}) de {$this->order->user->name} — KZ "
                . number_format((float) $this->order->total_price, 2, ',', '.'),
        ];
    }
}
