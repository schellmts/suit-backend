<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'customer_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:1,2,3,4,5,6,7,8',
            'type' => 'required|in:1,2,3,4,5',
            'area_customer' => 'required|string|max:50',
            'category' => 'required|string|max:50',
            'subcategory' => 'required|string|max:50',
            'tags' => 'nullable|string|max:1250',
            'priority' => 'nullable|in:1,2,3,4,5,6',
            'assigned_area' => 'nullable|string|max:50',
            // 'user_ticket_id' => 'required|string',
            // 'created_by' => 'required|string|max:255',
             'email_abertura_ticket' => 'nullable|email|max:250',
            'agent_id' => 'nullable|integer',
            'email_cc' => 'nullable|string|max:250',
            'date_exp_finish' => 'nullable|date',
            'date_open' => 'nullable|date',
            'date_finished' => 'nullable|date',
            'date_accept_customer' => 'nullable|date',
            'group_id' => 'nullable|integer',
            'ticket_origin' => 'nullable|in:1,2,3,4,5',
            'related_ticket_id' => 'nullable|integer|exists:ticket,id',
        ];
    }
}
