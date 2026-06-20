<?php

namespace App\Notifications;

use App\Models\OrderNegotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
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
        return [
            'order_id' => $this->order->id,
            'status'   => 'confirmed',
            'message'  => "A sua encomenda #{$this->order->id} foi confirmada. Obrigado pela compra!",
        ];
    }
}
