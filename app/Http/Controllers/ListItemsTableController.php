<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ListItemsTable;
use App\Models\ListTable;
use App\Models\Network;
use Illuminate\Http\Request;

class ListItemsTableController extends Controller
{
    public function index(Network $network, Account $account, ListTable $list)
    {
        $items = $list->items()->orderBy('id')->get();
        if ($items->isEmpty()) {
            return response()->json(['message' => 'Lista vazia.']);
        }

        return response()->json(['data' => $items]);
    }

    public function show(Network $network, Account $account, ListTable $list, ListItemsTable $item)
    {
        if ($item->list_id !== $list->id) {
            return response()->json(['message' => 'Este item não pertence à lista.'], 403);
        }

        return response()->json(['data' => $item]);
    }

    public function store(Network $network, Account $account, ListTable $list, Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
        ]);

        $item = ListItemsTable::create([
            'account_id' => $account->id,
            'list_id' => $list->id,
            'item_code' => $validated['item_code'],
            'item_name' => $validated['item_name'],
        ]);

        return response()->json(['message' => 'Item criado com sucesso.', 'data' => $item], 201);
    }

    public function update(Network $network, Account $account, ListTable $list, ListItemsTable $item, Request $request)
    {
        if ($item->list_id !== $list->id) {
            return response()->json(['message' => 'Este item não pertence à lista.'], 403);
        }

        $validated = $request->validate([
            'item_code' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
        ]);

        $item->update($validated);

        return response()->json(['message' => 'Item atualizado com sucesso.', 'data' => $item]);
    }

    public function destroy(Network $network, Account $account, ListTable $list, ListItemsTable $item)
    {
        if ($item->list_id !== $list->id) {
            return response()->json(['message' => 'Este item não pertence à lista.'], 403);
        }

        $item->delete();

        return response()->json(['message' => 'Item excluído com sucesso.']);
    }
}
