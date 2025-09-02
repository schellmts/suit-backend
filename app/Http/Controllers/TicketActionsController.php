<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\TicketActions;
use Illuminate\Http\Request;

class TicketActionsController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $actions = TicketActions::where('account_id', $account->id)->get();

        if ($actions->isEmpty()) {
            return response()->json([
                'message' => 'Nenhuma ação encontrada.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Ações encontradas.',
            'data' => $actions,
        ], 200);
    }

    public function store(Network $network, Account $account, Request $request)
    {
        $action = TicketActions::create([
            'account_id' => $account->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Condição criada com sucesso.',
            'data' => $action,
        ], 201);
    }
}
