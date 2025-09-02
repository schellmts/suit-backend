<?php

namespace App\Observers;

use App\Models\TicketMovement;
use App\Notifications\TicketNotification;

class TicketMovementObserver
{
    /**
     * Handle the TicketMovement "created" event.
     */
    public function created(TicketMovement $ticketMovement): void
    {
        $ticketMovement->ticket->creator->notify(new TicketNotification($ticketMovement->ticket, 'Nova Movimentação'));
    }

    /**
     * Handle the TicketMovement "updated" event.
     */
    public function updated(TicketMovement $ticketMovement): void
    {
        //
    }

    /**
     * Handle the TicketMovement "deleted" event.
     */
    public function deleted(TicketMovement $ticketMovement): void
    {
        //
    }

    /**
     * Handle the TicketMovement "restored" event.
     */
    public function restored(TicketMovement $ticketMovement): void
    {
        //
    }

    /**
     * Handle the TicketMovement "force deleted" event.
     */
    public function forceDeleted(TicketMovement $ticketMovement): void
    {
        //
    }
}
