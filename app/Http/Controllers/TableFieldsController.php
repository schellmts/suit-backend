<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\GenericTable;
use App\Models\GenericTableValue;
use App\Models\ListItemsTable;
use App\Models\ListTable;
use App\Models\Network;
use App\Models\TableField;
use Illuminate\Http\Request;

class TableFieldsController extends Controller
{
    public function index(Network $network, Account $account, GenericTable $table)
    {
        $fields = $table->fields()
            ->with('values')
            ->orderBy('id')
            ->get();

        if ($fields->isEmpty()) {
            return response()->json(['message' => 'Tabela vazia.']);
        }

        return response()->json(['data' => $fields]);
    }


    public function show(Network $network, Account $account, GenericTable $table, GenericTableValue $item)
    {
        if ($item->list_id !== $table->id) {
            return response()->json(['message' => 'Este item não pertence à tabela.'], 403);
        }

        return response()->json(['data' => $item]);
    }

    public function store(Network $network, Account $account, GenericTable $table, Request $request)
    {
        $validated = $request->validate([
            'cod_field' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'label' => 'required|string|max:50',
            'field_type' => 'required|string|max:100',
            'default_value' => 'nullable|string|max:2500',
        ]);

        $item = TableField::create([
            'account_id' => $account->id,
            'generic_table_id' => $table->id,
            'cod_field' => $validated['cod_field'],
            'description' => $validated['description'],
            'label' => $validated['label'],
            'field_type' => $validated['field_type'],
            'default_value' => $validated['default_value'],
        ]);

        return response()->json(['message' => 'Item criado com sucesso.', 'data' => $item], 201);
    }

    public function update(Network $network, Account $account, GenericTable $table, TableField $field, Request $request)
    {
        if ($field->generic_table_id !== $table->id) {
            return response()->json(['message' => 'Este item não pertence à lista.'], 403);
        }

        // Verifica se o campo tem valores associados
        $hasValues = $field->values()->exists();

        // Valida a requisição
        $validated = $request->validate([
            'cod_field' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
            'field_type' => 'nullable|string|max:100',
            'default_value' => 'nullable|string|max:2500',
            'label' => 'nullable|string|max:100',
        ]);

        // Se já existir valores e o tipo enviado for diferente do atual, bloqueia alteração
        if (
            $hasValues &&
            $request->has('field_type') &&
            strtolower($request->input('field_type')) !== strtolower($field->field_type)
        ) {
            return response()->json([
                'message' => 'Não é possível alterar o tipo de campo após ele possuir valores cadastrados.'
            ], 422);
        }
        if (
            $hasValues &&
            $request->has('cod_field') &&
            strtolower($request->input('cod_field')) !== strtolower($field->cod_field)
        ) {
            return response()->json([
                'message' => 'Não é possível alterar o código do campo após ele possuir valores cadastrados.'
            ], 422);
        }


        $field->update($validated);

        return response()->json([
            'message' => 'Item atualizado com sucesso.',
            'data' => $field
        ]);
    }


    public function destroy(Network $network, Account $account, GenericTable $table, TableField $field)
    {
        if ($field->generic_table_id !== $table->id) {
            return response()->json(['message' => 'Este item não pertence à tabela.'], 403);
        }

        $field->delete();

        return response()->json(['message' => 'Item excluído com sucesso.']);
    }
}
