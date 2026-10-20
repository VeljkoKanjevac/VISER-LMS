<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson_id' => [
                'required',
                'integer',
                Rule::exists('lessons', 'id'),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:20480',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}
