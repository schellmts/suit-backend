<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRuleActionStoreRequest;
use App\Http\Requests\TicketRuleConditionStoreRequest;
use App\Models\Account;
use App\Models\Network;
use App\Models\TicketRuleAction;
use App\Models\TicketRuleCondition;
use App\Models\TicketRuleGroup;
use Illuminate\Http\Request;

class TicketRuleActionsController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $ruleActions = TicketRuleAction::where('account_id', $account->id)->get();


        if ($ruleActions->isEmpty()) {
            return response()->json([
                'message' => 'Nenhuma Ação encontrado para esta conta.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Açoes encontradas.',
            'data' => $ruleActions,
        ], 200);
    }

    public function store(Network $network, Account $account, TicketRuleActionStoreRequest $request)
    {
        $validated = $request->validated();

        $condition = TicketRuleAction::create([
            'account_id' => $account->id,
            'rule_group_id' => $validated['rule_group_id'],
            'action_type_id' => $validated['action_type_id'],
            'action_value' => $validated['action_value'],
        ]);

        return response()->json([
            'message' => 'Ação criada com sucesso.',
            'data' => $condition,
        ], 201);
    }
}
