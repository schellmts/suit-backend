<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhysicalPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'country_code' => ['required', 'string', 'max:10'],
            'erp_physical_person_code' => ['nullable', 'string', 'max:50'],
            'document_type1' => ['nullable', 'integer'],
            'document1' => ['nullable', 'string', 'max:50'],
            'document_type2' => ['nullable', 'integer'],
            'document2' => ['nullable', 'string', 'max:50'],
            'passport' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'name' => ['required', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:250'],
            'neighborhood' => ['nullable', 'string', 'max:250'],
            'street' => ['nullable', 'string', 'max:250'],
            'extra_info1' => ['nullable', 'string', 'max:512'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'city_code' => ['nullable', 'string', 'max:50'],
            'number' => ['nullable', 'string', 'max:50'],
            'extra_info2' => ['nullable', 'string', 'max:80'],
            'nationality' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:80'],
            'gender' => ['nullable', 'in:male,female,other'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed,other']
        ];
    }
}
