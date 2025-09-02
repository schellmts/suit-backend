<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'account_id' => 'required|exists:accounts,id',
            'project_id' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:1,2,3,4,5,6',
            'type' => 'required|in:1,2,3,4,5',
            'area_customer' => 'required|string|max:50',
            'category' => 'required|string|max:50',
            'subcategory' => 'required|string|max:50',
            'tags' => 'nullable|string|max:1250',
            'priority' => 'nullable|in:1,2,3,4,5',
            'assigned_area' => 'nullable|string|max:50',
            'accuracy_resolution' => 'nullable|integer|between:1,10',
            'satisfaction_level' => 'nullable|integer|between:1,10',
            'obs_evaluation' => 'nullable|string|max:1250',
            'user_ticket_id' => 'required|string',
            'agent_id' => 'nullable|integer',
            'email_cc' => 'nullable|string|max:250',

            'date_exp_first_interaction' => 'nullable|date',
            'date_last_interaction' => 'nullable|date',
            'date_exp_finish' => 'nullable|date',
            'date_open' => 'nullable|date',
            'date_finished' => 'nullable|date',
            'date_accept_customer' => 'nullable|date',

            'group_id' => 'nullable|integer',
            'ticket_origin' => 'nullable|in:1,2,3,4,5',
            'email_abertura_ticket' => 'nullable|email|max:250',

            'ticket_budgeted_value' => 'nullable|numeric',

            'ticket_hours_aprov' => 'nullable|integer',
            'ticket_hours_work' => 'nullable|integer',
            'ticket_hours_lim' => 'nullable|integer',

            'reserved_1' => 'nullable|string|max:255',
            'reserved_2' => 'nullable|string|max:255',
            'reserved_3' => 'nullable|string|max:255',
            'reserved_4' => 'nullable|string|max:255',
            'reserved_5' => 'nullable|string|max:255',

            'created_by' => 'nullable|string|max:255',
            'updated_prog' => 'nullable|string|max:250',
            'created_prog' => 'nullable|string|max:250',

            'related_ticket_id' => 'nullable|integer|exists:ticket,id',
        ];
    }
}
