<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $course = $this->route('course');

        return [
            'faculty_id' => [
                'required',
                'integer',
                Rule::exists('faculties', 'id')
                    ->where(function ($query) use ($course) {
                        $query
                            ->where('is_active', true)
                            ->orWhere('id', $course->faculty_id);
                    }),
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
                Rule::unique('courses', 'slug')
                    ->ignore($course),
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
