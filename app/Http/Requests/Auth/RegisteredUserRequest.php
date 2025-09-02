<?php

namespace App\Http\Requests\Auth;

use App\Models\UserNetwork;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisteredUserRequest extends FormRequest
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role_id' => ['integer', 'exists:roles,id'],
            'customer_id' => ['uuid', 'exists:customers,id'],
            'supplier_id' => ['uuid', 'exists:suppliers,id'],
            'type' => ['required', 'string', Rule::in(array_values(UserNetwork::$TYPES))],
            function ($value, $fail) {
                $account = $this->route('account'); // Obtém o account da URL

                $exists = DB::table('user_accounts')->where('account_id', $account->id)
                    ->where('user_id', $value)
                    ->exists();

                if ($exists) {
                    $fail('User already linked');
                }
            },

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',

            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'The email must not exceed 255 characters.',

            'role_id.integer' => 'The role must be a integer.',
            'role_id.exists' => 'The role not exist.',

            'type.string' => 'The type must be a string.',
            'type.required' => 'The type is required.',
        ];
    }
}
