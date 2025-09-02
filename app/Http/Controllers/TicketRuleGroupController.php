<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\TicketRuleGroup;
use Illuminate\Http\Request;

class TicketRuleGroupController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $ruleGroups = $account->ticketRuleGroups()->with(['conditions', 'actions'])->get();

        if ($ruleGroups->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum grupo de regras encontrado para esta conta.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Grupos de regras encontrados.',
            'data' => $ruleGroups,
        ], 200);
    }

    public function store(Network $network, Account $account, Request $request)
    {
        $group = TicketRuleGroup::create([
            'account_id' => $account->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Grupo de atendimento criado com sucesso.',
            'data' => $group,
        ], 201);
    }
}
