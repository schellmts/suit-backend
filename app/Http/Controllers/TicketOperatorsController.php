<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\TicketOperator;
use Illuminate\Http\Request;

class TicketOperatorsController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $operator = TicketOperator::all();

        if ($operator->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum operador encontrado.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Operadores encontrados.',
            'data' => $operator,
        ], 200);
    }

    public function store(Network $network, Account $account, Request $request)
    {
        $operator = TicketOperator::create([
            'symbol' => $request->symbol,
        ]);

        return response()->json([
            'message' => 'Condição criada com sucesso.',
            'data' => $operator,
        ], 201);
    }
}
