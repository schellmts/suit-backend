<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRuleConditionStoreRequest;
use App\Models\Account;
use App\Models\Network;
use App\Models\TicketRuleCondition;
use App\Models\TicketRuleGroup;
use Illuminate\Http\Request;

class TicketRuleConditionsController extends Controller
{
    public function index(Network $network, Account $account, TicketRuleGroup $group)
    {
        $ruleConditions = TicketRuleCondition::where('account_id', $account->id)->get();


        if ($ruleConditions->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum grupo de condições encontrado para esta conta.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Grupos de regras encontrados.',
            'data' => $ruleConditions,
        ], 200);
    }

    public function store(Network $network, Account $account, TicketRuleConditionStoreRequest $request)
    {
        $validated = $request->validated();

        $condition = TicketRuleCondition::create([
            'account_id' => $account->id,
            'rule_group_id' => $validated['rule_group_id'],
            'condition_type_id' => $validated['condition_type_id'],
            'operator_id' => $validated['operator_id'],
            'value' => $validated['value'],
            'logic_operator' => $validated['logic_operator'] ?? null,
            'condition_group' => $validated['condition_group'] ?? null,
        ]);

        return response()->json([
            'message' => 'Condição criada com sucesso.',
            'data' => $condition,
        ], 201);
    }

}
