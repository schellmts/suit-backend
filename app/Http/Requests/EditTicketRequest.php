<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTicketRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'type' => 'nullable|in:1,2,3,4,5',
            'status' => 'nullable|in:1,2,3,4,5,6,7,8',
            'area_customer' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'subcategory' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:1250',
            'priority' => 'nullable|in:1,2,3,4,5,6',
            'assigned_area' => 'nullable|string|max:50',
            'obs_evaluation' => 'nullable|string|max:1250',
            'user_ticket_id' => 'nullable|string',
            'agent_id' => 'nullable|integer',
            'group_id' => 'nullable|integer',
            'email_cc' => 'nullable|string|max:250',
        ];
    }
}
