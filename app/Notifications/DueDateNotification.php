<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DueDateNotification extends Notification
{
    protected $type, $description, $dueDate;

    public function __construct($type, $description, $dueDate)
    {
        $this->type = $type;
        $this->description = $description;
        $this->dueDate = $dueDate;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Vencimento de {$this->type}")
            ->line("O {$this->type} '{$this->description}' vence em {$this->dueDate->format('d/m/Y')}.")
            ->action('Ver Detalhes', url('/admin/gestao/' . strtolower($this->type) . 's'));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Vencimento de {$this->type}: {$this->description} em {$this->dueDate->format('d/m/Y')}",
        ];
    }
}
