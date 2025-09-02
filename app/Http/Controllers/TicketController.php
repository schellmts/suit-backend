<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\EditTicketRequest;
use App\Http\Requests\TicketRequest;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Network;
use App\Models\Ticket;
use App\Models\TicketAgents;
use App\Models\TicketAssignment;
use App\Models\User;
use App\Models\UserCustomer;
use App\Services\RuleEngineService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $user = auth()->user();
        $networkPivot = $user->networks()->find($network->id)?->pivot;
        $isOwner = $networkPivot && $networkPivot->type === 'owner';
        $isAgent = $user->isAgentInAccount($account);

        $canViewAllTickets = $isOwner || $isAgent;

        $query = Ticket::where('account_id', $account->id);

        if (!$canViewAllTickets) {
            $query->where('created_by', $user->id);
        }

        $tickets = $query->with(['movements', 'account', 'creator', 'ticketAgent.user', 'ticketGroup'])
            ->orderBy('id', 'asc')
            ->get();

        $tickets->transform(function ($ticket) use ($user) {

            $visibleMovements = $ticket->movements->filter(function ($movement) use ($user) {
                return Gate::forUser($user)->allows('view', $movement);
            });

            $ticket->movements = $visibleMovements->values();

            if ($ticket->movements->isNotEmpty()) {
                $expirationDate = Carbon::parse($ticket->date_exp_finish);
            } else {
                $expirationDate = Carbon::parse($ticket->date_exp_first_interaction);
            }
            $ticket->remaining_time = $expirationDate->locale('pt_BR')->diffForHumans();
            $ticket->expiration_time = Carbon::now()->gt($expirationDate);

            return $ticket;
        });

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'Nenhum ticket encontrado para esta conta.'], 200);
        }

        return response()->json($tickets);
    }

    public function ticketAgents(Network $network, Account $account)
    {
        $agents = TicketAgents::with('user')
            ->where('account_id', $account->id)
            ->orderBy('id', 'asc')
            ->get();

        if ($agents->isEmpty()) {
            return response()->json(['message' => 'Você não tem agentes cadastrados.']);
        }

        return response()->json(['data' => $agents]);
    }

    public function store(Network $network, Account $account, CreateTicketRequest $request, RuleEngineService $ruleEngineService)
    {
        $user = request()->user();
        $data = $request->validated();

        $data['account_id'] = $account->id;
        $data['created_by'] = $user->id;

        $userCustomerLink = UserCustomer::where('user_id', $user->id)->first();

        if ($userCustomerLink) {
            $customerUuid = $userCustomerLink->customer_id;
            $customer = Customer::where('id', $customerUuid)->first();

            if ($customer) {
                $data['customer_id'] = $customer->id;
            }
        }

        $ticket = Ticket::create($data);

        $ruleEngineService->applyRules($ticket, $account);

        $ticket->refresh();

        $ticket->load('creator');

        return response()->json([
            'message' => 'Ticket criado com sucesso',
            'data' => $ticket,
        ], 201);
    }

    public function update(Network $network, Account $account, Ticket $ticket, EditTicketRequest $request)
    {
        if ($ticket->account_id !== $account->id) {
            return response()->json(['message' => 'Este ticket não pertence à conta.'], 403);
        }

        $ticket->update($request->validated());

        return response()->json(['message' => 'Ticket atualizado com sucesso', 'data' => $ticket]);
    }

    public function getTicketById(Request $request, Network $network, Account $account, Ticket $ticket)
    {
        if ($ticket->account_id !== $account->id) {
            return response()->json(['message' => 'Este ticket não pertence à conta fornecida.'], 403);
        }

        $ticket->load('creator');
        $ticket->load('ticketAgent.user');

        $ticket->load('movements');

        return response()->json(['data' => $ticket]);
    }

    public function destroy(Request $request, Network $network, Account $account, Ticket $ticket)
    {
        if ($ticket->account_id !== $account->id) {
            return response()->json(['message' => 'Este ticket não pertence à conta fornecida.'], 403);
        }

        $ticket->delete();

        return response()->json(['message' => 'Tabela excluída com sucesso.']);
    }

    public function ticketByHour(Network $network, Account $account)
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        $tickets = Ticket::where('account_id', $account->id)
            ->where(function ($query) use ($today, $yesterday) {
                $query->whereDate('created_at', $today)
                    ->orWhereDate('created_at', $yesterday);
            })
            ->get();

        $todayHours = array_fill(0, 24, 0);
        $yesterdayHours = array_fill(0, 24, 0);

        foreach ($tickets as $ticket) {
            $hour = (int) $ticket->created_at->format('H');
            $isToday = $ticket->created_at->isToday();
            $isYesterday = $ticket->created_at->isSameDay($yesterday);

            if ($isToday) {
                $todayHours[$hour]++;
            } elseif ($isYesterday) {
                $yesterdayHours[$hour]++;
            }
        }

        return response()->json([
            'labels' => array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . 'h', range(0, 23)),
            'series' => [
                'today' => $todayHours,
                'yesterday' => $yesterdayHours,
            ],
        ]);
    }

    public function getTicketStats(Network $network, Account $account)
    {
        $tickets = Ticket::where('account_id', $account->id)->get();

        $resolvidos = 0;
        $recebidos = $tickets->count();
        $tempoRespostaTotal = 0;
        $slaTotal = 0;

        foreach ($tickets as $ticket) {
            if ($ticket->status == 5) {
                $resolvidos++;
            }

            if ($ticket->date_finished) {
                $tempoRespostaTotal += $ticket->date_finished->diffInHours($ticket->date_open);
            }

            if ($ticket->date_exp_finish) {
                $slaTotal += \Carbon\Carbon::parse($ticket->date_exp_finish)->isPast() ? 0 : 1;
            }
        }

        $slaPercentual = $recebidos > 0 ? ($slaTotal / $recebidos) * 100 : 0;

        $tempoRespostaHoras = $recebidos > 0 ? $tempoRespostaTotal / $recebidos : 0;

        return response()->json([
            'resolvidos' => $resolvidos,
            'recebidos' => $recebidos,
            'tempoRespostaHoras' => round($tempoRespostaHoras, 2),
            'slaPercentual' => round($slaPercentual, 2),
        ]);
    }

    public function getAssignedTicketsByUser(Network $network, Account $account)
    {
        $user = Auth::user();

        $userGroupIds = $user->ticketGroups()
            ->where('ticket_groups.account_id', $account->id)
            ->pluck('ticket_groups.id');

        $userAssignedTickets = TicketAssignment::where('assigned_user_id', $user->id)
            ->where('account_id', $account->id)
            ->where('is_active', true)
            ->with('ticket')
            ->get()
            ->pluck('ticket')
            ->filter();

        $groupAssignedTickets = Ticket::whereIn('group_id', $userGroupIds)
            ->where('account_id', $account->id)
            ->get();

        $allTickets = $userAssignedTickets->merge($groupAssignedTickets)->unique('id');

        $grouped = collect(range(1, 6))->mapWithKeys(function ($status) use ($allTickets) {
            return [$status => $allTickets->where('status', (string)$status)->values()];
        });

        return response()->json($grouped);
    }

    public function getTicketsAssignedToAllAgents(Network $network, Account $account)
    {
        $user = Auth::user();

        $networkPivot = $user->networks()->find($network->id)->pivot;
        $isOwner = $networkPivot && $networkPivot->type === 'owner';

        if (!$isOwner) {
            return response()->json(['message' => 'Acesso não autorizado.'], 403);
        }

        $allAgents = TicketAgents::where('account_id', $account->id)
            ->with('user')
            ->get();

        if ($allAgents->isEmpty()) {
            return response()->json([]);
        }

        $agentIds = $allAgents->pluck('user_id');

        $assignedTickets = Ticket::where('account_id', $account->id)
            ->whereIn('agent_id', $agentIds)
            ->with(['creator', 'ticketGroup'])
            ->get();

        $ticketsGroupedByAgent = $assignedTickets->groupBy('agent_id');

        $response = $allAgents->map(function ($ticketAgent) use ($ticketsGroupedByAgent) {
            $agentInfo = $ticketAgent->user;
            $agentId = $agentInfo->id;

            $agentTickets = $ticketsGroupedByAgent->get($agentId) ?? [];

            return [
                'agent' => [
                    'id' => $agentInfo->id,
                    'name' => $agentInfo->name,
                    'email' => $agentInfo->email,
                ],
                'tickets' => $agentTickets,
            ];
        });

        return response()->json($response);
    }

    public function getExpiredTickets(Network $network, Account $account)
    {
//        $user = request()->user();
//        $isOwner = $user->id === $account->id;
//        $now = Carbon::now();
//
//        $query = Ticket::where('account_id', $account->id);
//
//        if (!$isOwner) {
//            $query->where('created_by', $user->id);
//        }
//
//        $firstInteractionExpired = (clone $query)
//            ->where('date_exp_first_interaction', '<', $now)
//            ->where('status', '7')
//            ->with(['movements', 'creator', 'ticketAgent.user', 'ticketGroup'])
//            ->orderBy('date_exp_first_interaction', 'asc')
//            ->take(5)
//            ->get();
//
//        $finishExpired = (clone $query)
//            ->where('date_exp_finish', '<', $now)
//            ->whereNotIn('status', ['4', '5', '6', '7'])
//            ->with(['movements', 'creator', 'ticketAgent.user', 'ticketGroup'])
//            ->get();
//
//        $firstInteractionExpired = $firstInteractionExpired->map(function ($ticket) {
//            $expirationDate = Carbon::parse($ticket->date_exp_first_interaction);
//            $ticket->expired_ago = $expirationDate->diffForHumans();
//
//            return $ticket;
//        });
//
//        return response()->json([
//            'first_interaction_expired' => $firstInteractionExpired,
//            'finish_expired' => $finishExpired,
//        ]);

        $user = auth()->user();
        $networkPivot = $user->networks()->find($network->id)->pivot;
        $isOwner = $networkPivot && $networkPivot->type === 'owner';

        $isAgent = TicketAgents::where('user_id', $user->id)
            ->where('account_id', $account->id)
            ->exists();

        $canViewAllTickets = $isOwner || $isAgent;

        $now = Carbon::now();

        $query = Ticket::where('account_id', $account->id);

        if (!$canViewAllTickets) {
            $query->where('created_by', $user->id);
        }

        $firstInteractionExpired = (clone $query)
            ->where('date_exp_first_interaction', '<', $now)
            ->where('status', '7')
            ->with(['movements', 'creator', 'ticketAgent.user', 'ticketGroup'])
            ->orderBy('date_exp_first_interaction', 'asc')
            ->take(5)
            ->get();

        $finishExpired = (clone $query)
            ->where('date_exp_finish', '<', $now)
            ->whereNotIn('status', ['4', '5', '6', '7'])
            ->with(['movements', 'creator', 'ticketAgent.user', 'ticketGroup'])
            ->get();

        $firstInteractionExpired = $firstInteractionExpired->map(function ($ticket) {
            $expirationDate = Carbon::parse($ticket->date_exp_first_interaction);
            $ticket->expired_ago = $expirationDate->locale('pt_BR')->diffForHumans();

            return $ticket;
        });

        return response()->json([
            'first_interaction_expired' => $firstInteractionExpired,
            'finish_expired' => $finishExpired,
        ]);
    }

    public function getSoonToExpireTickets(Network $network, Account $account)
    {
//        $user = request()->user();
//        $isOwner = $user->id === $account->id;
//        $now = Carbon::now();
//
//        $expirationThreshold = Carbon::now()->addHours(24);
//
//        $query = Ticket::where('account_id', $account->id);
//
//        if (!$isOwner) {
//            $query->where('created_by', '!=', $user->id);
//        }
//
//        $soonToExpire = (clone $query)
//            ->where('date_exp_first_interaction', '>', $now)
//            ->where('date_exp_first_interaction', '<', $expirationThreshold)
//            ->where('status', '1')
//            ->with(['creator', 'ticketAgent.user', 'ticketGroup'])
//            ->orderBy('date_exp_first_interaction', 'asc')
//            ->take(5)
//            ->get();
//
//        $soonToExpire = $soonToExpire->map(function ($ticket) {
//            $expirationDate = Carbon::parse($ticket->date_exp_first_interaction);
//            $ticket->time_left = $expirationDate->diffForHumans(['parts' => 2, 'short' => true]);
//            return $ticket;
//        });
//
//        return response()->json([
//            'soon_to_expire_first_interaction' => $soonToExpire,
//        ]);

        $user = auth()->user();

        $networkPivot = $user->networks()->find($network->id)->pivot;
        $isOwner = $networkPivot && $networkPivot->type === 'owner';

        $isAgent = TicketAgents::where('user_id', $user->id)
            ->where('account_id', $account->id)
            ->exists();

        $canViewAllTickets = $isOwner || $isAgent;

        $now = Carbon::now();
        $expirationThreshold = Carbon::now()->addHours(24);

        $query = Ticket::where('account_id', $account->id);

        if (!$canViewAllTickets) {
            $query->where('created_by', $user->id);
        }

        $soonToExpire = (clone $query)
            ->where('date_exp_first_interaction', '>', $now)
            ->where('date_exp_first_interaction', '<', $expirationThreshold)
            ->where('status', '1')
            ->with(['creator', 'ticketAgent.user', 'ticketGroup'])
            ->orderBy('date_exp_first_interaction', 'asc')
            ->take(5)
            ->get();

        $soonToExpire = $soonToExpire->map(function ($ticket) {
            $expirationDate = Carbon::parse($ticket->date_exp_first_interaction);
            $ticket->time_left = $expirationDate->locale('pt_BR')->diffForHumans(['parts' => 2]);
            return $ticket;
        });

        return response()->json([
            'soon_to_expire_first_interaction' => $soonToExpire,
        ]);
    }

    public function getTicketCountByCustomer(Network $network, Account $account)
    {
        $stats = Ticket::join('customers', 'ticket.customer_id', '=', 'customers.id')
        ->select(
            'customers.name as customer_name',
            DB::raw('COUNT(ticket.id) as tickets_count')
        )
            ->where('ticket.account_id', $account->id)
            ->whereNotNull('ticket.customer_id')
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('tickets_count', 'desc')
            ->take(5)
            ->get();

        return response()->json($stats);
    }
}
