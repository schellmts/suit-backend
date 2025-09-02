<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class GroupingSkillRequest extends FormRequest
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
            'skill_id' => [
                'required',
                'exists:skills,id',
                function ($attribute, $value, $fail) {
                    $grouping = $this->route('grouping'); // Obtém o grouping da URL

                    $exists = DB::table('grouping_skills')->where('grouping_id', $grouping->id)
                        ->where('skill_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Skill already linked');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'skill_id.required' => 'The skill are required.',
            'skill_id.exists' => 'The skill needs to exist.',
        ];
    }
}
