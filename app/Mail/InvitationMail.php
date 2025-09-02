<?php

namespace App\Mail;

use App\Models\Account;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Invitation $invitation)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build(): static {
        $account = Account::find($this->invitation->account_id);
        $networkId = $account->network_id; // Supondo que a relação network_id está na tabela Account

        return $this->subject('Você foi convidado para participar')
            ->markdown('emails.Invite')
            ->with([
                'invite' => $this->invitation,
                'account' => $account,
                'acceptUrl' => env('APP_URL_FRONT') . '/accept-invitation-user?token=' . $this->invitation->token
                    . '&email=' . $this->invitation->email
                    . '&network=' . $networkId  // Adicionando o network_id
                    . '&account=' . $this->invitation->account_id  // Adicionando o account_id
            ]);
    }

}
