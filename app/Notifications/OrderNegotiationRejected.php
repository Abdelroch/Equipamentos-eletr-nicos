<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderNegotiationRejected extends Notification
{
    use Queueable;

    public function __construct(public readonly mixed $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Tenta obter o valor da proposta — o campo pode chamar-se proposed_price ou offer
        $offerAmount = $this->order->proposed_price
                    ?? $this->order->offer
                    ?? 0;

        $productName = $this->order->product->nome
                    ?? $this->order->product_name
                    ?? 'N/D';

        // O motivo pode vir de admin_notes (campo real da tabela)
        $reason = $this->order->admin_notes ?? null;

        return [
            'title'    => 'Proposta rejeitada',
            'message'  => 'A sua proposta de KZ ' .
                          number_format($offerAmount, 2, ',', '.') .
                          ' para o produto "' . $productName . '" foi rejeitada.',
            'reason'   => $reason,
            'order_id' => $this->order->id,
            'type'     => 'negotiation_rejected',
        ];
    }
}
