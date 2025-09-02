<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketMovementRequest extends FormRequest
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
            'body' => 'required|string',
            'email_cc' => 'nullable|string|max:250',
            'privacity' => 'required|in:1,2,3',
            'status' => 'nullable|integer|in:1,2,3,4,5,6',
            'type' => 'required|in:1,2',
            'user_id' => 'nullable|string',
            'origin' => 'required|in:1,2,3',
            'created_by' => 'required|string|max:255'
        ];
    }
}
