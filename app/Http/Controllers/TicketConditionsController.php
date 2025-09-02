<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\TicketCondition;
use App\Models\TicketRuleGroup;
use Illuminate\Http\Request;

class TicketConditionsController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $conditions = TicketCondition::where('account_id', $account->id)->get();

        if ($conditions->isEmpty()) {
            return response()->json([
                'message' => 'Nenhuma condição encontrada.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Condições encontradas.',
            'data' => $conditions,
        ], 200);
    }


    public function store(Network $network, Account $account, Request $request)
    {
        $group = TicketCondition::create([
            'account_id' => $account->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Condição criada com sucesso.',
            'data' => $group,
        ], 201);
    }
}
