<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $section = $this->route('section');

        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')
                    ->where(function ($query) use ($section) {
                        $query
                            ->where('is_active', true)
                            ->orWhere('id', $section->course_id);
                    }),
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
