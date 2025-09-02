<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ProjectUserGroupingStoreRequest extends FormRequest
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
            'user_grouping_id' => [
                'required',
                'exists:user_groupings,id',
                function ($attribute, $value, $fail) {
                    $project = $this->route('project');

                    $exists = DB::table('project_user_groupings')->where('project_id', $project->id)
                        ->where('user_grouping_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Resource already linked');
                    }
                },
            ],
        ];
    }
}
