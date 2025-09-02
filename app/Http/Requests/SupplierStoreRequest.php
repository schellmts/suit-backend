<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\Rules\Phone;

class SupplierStoreRequest extends FormRequest
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
            'account_id'         => ['required', 'exists:accounts,id'],
            'type'               => ['required', 'in:individual,business'],
            'name'               => ['required', 'string', 'max:255'],
            'document_number'    => ['required', 'string', 'max:100'],
            'document_type'      => ['required', 'string', 'max:50'],
            'email'              => ['required', 'email', 'max:255'],
            'phone'              => ['required', new Phone],
            'country'            => ['required', 'string', 'size:2'],
            'state'              => ['required', 'string', 'max:100'],
            'city'               => ['required', 'string', 'max:100'],
            'postal_code'        => ['required', 'string', 'max:20'],
            'address_line'       => ['required', 'string', 'max:255'],
            'timezone'           => ['required', 'timezone'],
            'preferred_language' => ['required', 'string', 'size:2'],
            'metadata'           => ['nullable', 'string'],
        ];
    }
}
