<?php

namespace App\Notifications;

use App\Models\OrderNegotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderAccepted extends Notification
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
            'order_id'     => $this->order->id,
            'status'       => 'accepted',
            'message'      => "A sua oferta para a encomenda #{$this->order->id} foi aceite! Tem "
                . \App\Services\OrderNegotiationService::RESERVATION_HOURS
                . "h para submeter o comprovativo de pagamento.",
            'reserved_until' => optional($this->order->reserved_until)->toDateTimeString(),
        ];
    }
}
