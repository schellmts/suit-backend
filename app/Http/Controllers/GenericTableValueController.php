<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\GenericTable;
use App\Models\GenericTableValue;
use App\Models\Network;
use App\Models\TableField;
use Illuminate\Http\Request;

class GenericTableValueController extends Controller
{
    public function index(Network $network, Account $account, GenericTableValue $table)
    {
        $items = $table->field()->orderBy('id')->get();
        if ($items->isEmpty()) {
            return response()->json(['message' => 'Tabela vazia.']);
        }

        return response()->json(['data' => $items]);
    }

    public function store(Network $network, Account $account, GenericTable $table, TableField $field, Request $request)
    {
        $validated = $request->validate([
            'value_field' => 'required|string|max:2500',
        ]);

        $item = GenericTableValue::create([
            'account_id' => $account->id,
            'generic_table_id' => $table->id,
            'field_id' => $field->id,
            'value_field' => $validated['value_field'],
        ]);

        return response()->json(['message' => 'Item criado com sucesso.', 'data' => $item], 201);
    }

    public function storeMany(Network $network, Account $account, GenericTable $table, Request $request)
    {
        $validated = $request->validate([
            'values' => 'required|array',
            'values.*.field_id' => 'required|exists:table_fields,id',
            'values.*.value_field' => 'required|max:2500',
        ]);

        if ($table->type_values == 1) {
            $existing = GenericTableValue::where('generic_table_id', $table->id)
                ->where('reg_id', 1)
                ->exists();

            if ($existing) {
                return response()->json([
                    'message' => 'Essa tabela permite apenas um registro.'
                ], 422);
            }

            $nextRegId = 1;
        } else {
            $maxRegId = GenericTableValue::where('generic_table_id', $table->id)->max('reg_id');
            $nextRegId = $maxRegId ? $maxRegId + 1 : 1;
        }

        $table->seq_values = $nextRegId;
        $table->save();


        $inserted = [];
        foreach ($validated['values'] as $value) {
            $inserted[] = GenericTableValue::create([
                'account_id' => $account->id,
                'generic_table_id' => $table->id,
                'field_id' => $value['field_id'],
                'value_field' => $value['value_field'],
                'reg_id' => $nextRegId,
            ]);
        }

        return response()->json([
            'message' => 'Valores inseridos com sucesso.',
            'data' => $inserted
        ], 201);
    }


    public function updateMany(Network $network, Account $account, GenericTable $table, Request $request)
    {
        $validated = $request->validate([
            'values' => 'required|array',
            'values.*.field_id' => 'required|exists:table_fields,id',
            'values.*.value_field' => 'required|max:2500',
            'values.*.reg_id' => 'required|integer',
        ]);

        $regId = $validated['values'][0]['reg_id'];

        foreach ($validated['values'] as $value) {
            GenericTableValue::updateOrCreate(
                [
                    'account_id' => $account->id,
                    'generic_table_id' => $table->id,
                    'field_id' => $value['field_id'],
                    'reg_id' => $value['reg_id'],
                ],
                [
                    'value_field' => $value['value_field'],
                ]
            );
        }

        return response()->json([
            'message' => 'Valores atualizados com sucesso.'
        ]);
    }

    public function deleteMany(Network $network, Account $account, GenericTable $table, Request $request)
    {
        $validated = $request->validate([
            'values' => 'required|array',
            'values.*.id' => 'required|integer|exists:generic_tables_values,id',
        ]);

        $deletedCount = GenericTableValue::where('generic_table_id', $table->id)
            ->whereIn('id', collect($validated['values'])->pluck('id'))
            ->where('account_id', $account->id)
            ->delete();

        return response()->json([
            'message' => "{$deletedCount} valores excluídos com sucesso."
        ]);
    }



    public function destroy(Network $network, Account $account, GenericTable $table, TableField $field, GenericTableValue $value)
    {
        if ($value->generic_table_id !== $table->id) {
            return response()->json(['message' => 'Este item não pertence à tabela.'], 403);
        }

        $value->delete();

        return response()->json(['message' => 'Valor excluído com sucesso.']);
    }


}
