<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ValidateContent implements ValidationRule
{
    /**
     * Validation method.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail("The field {$attribute} must be an array.");
            return;
        }

        $this->validateRecursive($value, $attribute, $fail);
    }
    private function validateRecursive(array $items, string $path, Closure $fail): void
    {
        foreach ($items as $index => $item) {
            $validator = Validator::make($item, [
                "name" => 'required|string',
                "duration" => 'nullable|string',
                "plan_date" => 'nullable|date',
                "end_plan_date" => 'nullable|date',
                "predecessor" => 'nullable|number',
                "resource" => 'nullable|array',
                // "partial_closure" => 'nullable',
                // "ont" => 'nullable',
                // "cr" => 'nullable',
                // "ept" => 'nullable',
                // "ent" => 'nullable',
                "children" => 'nullable|array',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    foreach ($messages as $message) {
                        $fail("{$path}.{$index}.{$field}")->translate();
                    }
                }
            }

            if (!empty($item['children']) && is_array($item['children'])) {
                $this->validateRecursive($item['children'], "{$path}.{$index}.children", $fail);
            }
        }
    }

    /**
     * Custom message
     *
     * @return string
     */
    public function message()
    {
        return 'The content is wrong';
    }
}
