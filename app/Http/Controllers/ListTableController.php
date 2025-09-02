<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ListTable;
use App\Models\Network;
use Illuminate\Http\Request;

class ListTableController extends Controller
{
    public function index(Network $network, Account $account)
    {
        $response = ListTable::where('account_id', $account->id)
            ->with('items')
            ->orderBy('id', 'asc')
            ->get() ?? [];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'list_code' => 'required|string|max:20',
            'list_name' => 'required|string|max:50',
        ]);

        return ListTable::create($validated);
    }

    public function update(Network $network, Account $account, ListTable $list, Request $request)
    {
        if ($list->account_id !== $account->id) {
            return response()->json(['message' => 'Esta lista não pertence à conta.'], 403);
        }

        $request->validate([
            'list_code' => 'required|string|max:20',
            'list_name' => 'required|string|max:50',
        ]);

        $list->update($request->only(['list_code', 'list_name']));

        return response()->json(['message' => 'Lista atualizada com sucesso', 'data' => $list]);
    }

    public function getListById(Network $network, Account $account, ListTable $list, Request $request)
    {
        if ($list->account_id !== $account->id) {
            return response()->json(['message' => 'Esta lista não pertence à conta fornecida.'], 403);
        }

        return response()->json(['data' => $list]);
    }

    public function destroy(Network $network, Account $account, ListTable $list)
    {
        if ($list->account_id !== $account->id) {
            return response()->json(['message' => 'Esta lista não pertence à conta fornecida.'], 403);
        }

        $list->items()->delete();

        $list->delete();

        return response()->json(['message' => 'Lista excluída com sucesso.']);
    }

}
