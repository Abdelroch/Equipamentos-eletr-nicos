<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowBalanceNotification extends Notification
{
    protected $balance;

    public function __construct($balance)
    {
        $this->balance = $balance;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Alerta: Saldo Baixo')
            ->line('O saldo da empresa está abaixo do limite.')
            ->line('Saldo atual: ' . number_format($this->balance, 2, ',', '.') . ' KZ')
            ->action('Ver Finanças', url('/admin/gestao/financeiros'));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Saldo baixo: ' . number_format($this->balance, 2, ',', '.') . ' KZ',
        ];
    }
}
