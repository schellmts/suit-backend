<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCheckoutRequest extends FormRequest
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
            'prices' => ['required', 'array'],
            'account' => ['required', 'integer']
        ];
    }

    function messsages()
    {
        return [
            'prices' => 'The product and prices are required.',
            'account' => 'The account ID are required.'
        ];
    }
}
