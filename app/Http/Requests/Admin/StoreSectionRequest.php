<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')
                    ->where('is_active', true),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_published' => [
                'required',
                'boolean',
            ],
        ];
    }
}
