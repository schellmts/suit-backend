<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'user_manager' => ['required', 'integer', 'exists:users,id'],
            'customer_id' => ['required', 'uuid', 'exists:customers,id'],
            'user_customer_approver' => ['nullable', 'integer', 'exists:users,id'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:planning,released,in progress,suspended,canceled,completed'],
            'manual' => ['required', 'boolean'],
            'with_warranty' => ['required', 'boolean'],
            'warranty_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'content' => ['required', 'array', new \App\Rules\ValidateContent()],
        ];
    }

    public function messages()
{
    return [
        'content.required' => 'O campo conteúdo é obrigatório.',
        'content.array' => 'O conteúdo fornecido deve ser um array.',
    ];
}
}
