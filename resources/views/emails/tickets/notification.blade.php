@component('mail::message')
    # Olá {{ $notifiable->name }},

    {{ $message }} para o ticket **#{{ $ticket->id }}**.

    @component('mail::button', ['url' => url('/tickets/' . $ticket->id)])
        Ver ticket
    @endcomponent

    Obrigado por usar nosso sistema!
    **Suit In**
@endcomponent
