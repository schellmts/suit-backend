<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRuleConditionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rule_group_id' => 'required|exists:ticket_rule_groups,id',
            'condition_type_id' => 'required|exists:ticket_conditions,id',
            'operator_id' => 'required|exists:ticket_operators,id',
            'value' => 'required|string',
            'logic_operator' => 'nullable|string|in:and,or',
            'condition_group' => 'nullable|integer',
        ];
    }
}
