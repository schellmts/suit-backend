<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketMovementRequest;
use App\Models\Account;
use App\Models\Network;
use App\Models\Ticket;
use App\Models\TicketMovement;
use Illuminate\Http\Request;

class TicketMovementController extends Controller
{
    public function index(Network $network, Account $account, Ticket $ticket)
    {
        $user = auth()->user();

        $movementsQuery = $ticket->movements();

        $movementsQuery->where(function ($query) use ($user, $ticket, $account, $network) {
            $query->where('privacity', '1');
            if ($user && ($user->isOwnerOfNetwork($network) || $user->isAgentInAccount($account))) {
                $query->orWhere('privacity', '2');
            }

            if ($user && $ticket->ticketAgent()->where('user_id', $user->id)->exists()) {
                $query->orWhere('privacity', '3');
            }
        });

        $movements = $movementsQuery->orderBy('id', 'asc')->get();

        if ($movements->isEmpty()) {
            return response()->json(['message' => 'Nenhum movimento visível para você neste ticket.'], 200);
        }

        return response()->json(['data' => $movements]);
    }


    public function create(Network $network, Account $account, Ticket $ticket, CreateTicketMovementRequest $request)
    {
        $data = $request->validated();

        $movement = $ticket->movements()->create([
            'account_id' => $account->id,
            'ticket_id' => $ticket->id,
            'body' => $data['body'],
            'email_cc' => $data['email_cc'] ?? null,
            'privacity' => $data['privacity'],
            'status' => $data['status'],
            'type' => $data['type'],
            'user_id' => $data['user_id'] ?? null,
            'origin' => $data['origin'],
            'created_by' => $data['created_by']
        ]);

        return response()->json([
            'message' => 'Movimento criado com sucesso.',
            'data' => $movement
        ], 201);
    }

    public function show(TicketMovement $movement)
    {
        $this->authorize('view', $movement);

        return response()->json($movement);
    }


}
