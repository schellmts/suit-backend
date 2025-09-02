<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $ticket, public $message)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->message)
            ->greeting('Olá ' . $notifiable->name . ',')
            ->line($this->message . ' para o ticket abaixo:')
            ->line('Título: ' . $this->ticket->title)
            ->line('Prioridade: ' . $this->ticket->priority)
            ->line('Status: ' . $this->ticket->status_text)
            ->action('Ver ticket', url('/tickets/' . $this->ticket->id))
            ->line('Este é um e-mail informativo, você não pode editar o ticket por aqui.')
            ->line('Obrigado por usar nosso sistema!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => $this->message,
        ];
    }
}
