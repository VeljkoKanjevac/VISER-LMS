<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_id' => [
                'required',
                'integer',
                Rule::exists('faculties', 'id')
                    ->where('is_active', true),
            ],

            'study_year' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:courses,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}
