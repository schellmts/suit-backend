<?php

namespace App\Policies;

use App\Models\TicketMovement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketMovementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketMovement $ticketMovement): bool
    {
        $ticket = $ticketMovement->ticket;
        if (!$ticket || !$ticket->account || !$ticket->account->network) {
            return false;
        }
        $account = $ticket->account;
        $network = $ticket->account->network;

        switch ($ticketMovement->privacity) {
            case '1':
                return true;

            case '2':
                return $user->isOwnerOfNetwork($network) || $user->isAgentInAccount($account);

            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketMovement $ticketMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketMovement $ticketMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketMovement $ticketMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketMovement $ticketMovement): bool
    {
        return false;
    }
}
