<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Notifications\TicketNotification;
use App\Services\SlaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $this->calculateAndSetSlaDates($ticket);

        $ticket->creator->notify(new TicketNotification($ticket, 'Ticket criado'));

        if (!is_null($ticket->agent_id)) {
           $this->assignAgent($ticket, $ticket->agent_id, $ticket->user_ticket_id);
        }
    }

    public function updated(Ticket $ticket): void
    {
        if ($ticket->isDirty('priority')) {
            $this->calculateAndSetSlaDates($ticket);
        }

        if ($ticket->wasChanged('agent_id') && !is_null($ticket->agent_id)) {
            $this->assignAgent($ticket, $ticket->agent_id, Auth::id());
        }

        $ticket->creator->notify(new TicketNotification($ticket, 'Ticket atualizado'));
    }

    protected function calculateAndSetSlaDates(Ticket $ticket): void
    {
        if ($ticket->priority) {
            $slaService = new SlaService();
            $ticket->date_exp_first_interaction = $slaService->calculateFirstInteractionDate($ticket->priority);
            $ticket->date_exp_finish = $slaService->calculateDueDate($ticket->priority);
            $ticket->saveQuietly();
        }
    }

    public function assignAgent(Ticket $ticket, $agentId, $assignedBy)
    {
        DB::transaction(function () use ($ticket, $agentId, $assignedBy) {
            TicketAssignment::where('ticket_id', $ticket->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            TicketAssignment::create([
                'ticket_id' => $ticket->id,
                'account_id' => $ticket->account_id,
                'assigned_user_id' => $agentId,
                'assigned_by' => $assignedBy,
                'is_active' => true,
            ]);
        });
    }

    /**
     * Handle the Ticket "updated" event.
     */


    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        $ticket->creator->notify(new TicketNotification($ticket, 'Ticket excluído'));
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
