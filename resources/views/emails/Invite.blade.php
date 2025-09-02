@component('mail::message')
# Você foi convidado!

Olá,

Você recebeu um convite para participar da conta **{{ $account->name }}** como **{{ ucfirst($invite->type) }}**.

Clique no botão abaixo para aceitar o convite e completar seu cadastro:

@component('mail::button', ['url' => $acceptUrl])
Aceitar Convite
@endcomponent

Este convite expira em {{ \Carbon\Carbon::parse($invite->expires_at)->diffForHumans() }}.

Se você não esperava este e-mail, pode ignorá-lo.

Atenciosamente,
Suit In
@endcomponent