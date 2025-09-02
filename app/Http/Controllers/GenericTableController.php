<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\GenericTable;
use App\Models\ListTable;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenericTableController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $response = GenericTable::where('account_id', $account->id)
            ->with(['fields', 'fields.values'])
            ->orderBy('id', 'asc')
            ->get();

        if ($response->isEmpty()) {
            return response()->json(['message' => 'Sem registros de Tabelas para o usuário']);
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'table_code' => 'required|string|max:50',
            'table_desc' => 'required|string|max:100',
            'type_values' => 'required|integer|max:5',
        ]);

        $table = GenericTable::create($validator);

        return response()->json($table, 201);
    }

    public function update(Network $network, Account $account, GenericTable $table, Request $request)
    {
        if ($table->account_id !== $account->id) {
            return response()->json(['message' => 'Esta tabela não pertence à conta.'], 403);
        }

         $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'table_code' => 'required|string|max:50',
            'table_desc' => 'required|string|max:100',
            'type_values' => 'required|integer|max:5',
        ]);

        $table->update($request->only(['table_code', 'table_desc', 'type_values']));

        return response()->json(['message' => 'Tabela atualizada com sucesso', 'data' => $table]);
    }

    public function getTableById(Request $request, Network $network, Account $account, GenericTable $table)
    {
        if ($table->account_id !== $account->id) {
            return response()->json(['message' => 'Esta tabela não pertence à conta fornecida.'], 403);
        }

        return response()->json(['data' => $table]);
    }

    public function destroy(Request $request, Network $network, Account $account, GenericTable $table)
    {
        if ($table->account_id !== $account->id) {
            return response()->json(['message' => 'Esta tabela não pertence à conta fornecida.'], 403);
        }

        $table->values()->delete();

        $table->delete();

        return response()->json(['message' => 'Tabela excluída com sucesso.']);
    }
}
