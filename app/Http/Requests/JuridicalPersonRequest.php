<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JuridicalPersonRequest extends FormRequest
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
            'document_type1' => ['nullable', 'integer'],
            'document1' => ['nullable', 'string', 'max:50'],
            'document_type2' => ['nullable', 'integer'],
            'document2' => ['nullable', 'string', 'max:50'],
            'document_type3' => ['nullable', 'integer'],
            'document3' => ['nullable', 'string', 'max:50'],
            'company_opening_date' => ['nullable', 'date'],
            'city' => ['nullable', 'string', 'max:250'],
            'neighborhood' => ['nullable', 'string', 'max:250'],
            'street' => ['nullable', 'string', 'max:250'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:80'],
            'business_area' => ['nullable', 'string', 'max:100'],
            'corporate_name' => ['nullable', 'string', 'max:150'],
            'trade_name' => ['nullable', 'string', 'max:150'],
            'company_type' => ['nullable', 'in:ltda,sa,micro_enterprise,ngo'],
            'number' => ['nullable', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:100'],
            'status' => ['boolean']
        ];
    }
}
