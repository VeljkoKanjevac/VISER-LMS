<?php

namespace App\Http\Requests\Admin;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'video_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_free' => [
                'required',
                'boolean',
            ],

            'is_published' => [
                'required',
                'boolean',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lessons', 'slug')
                    ->where(
                        fn ($query) => $query->where(
                            'section_id',
                            $this->input('section_id')
                        )
                    ),
            ],

            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')
                    ->whereIn(
                        'course_id',
                        Course::query()
                            ->where('is_active', true)
                            ->select('id')
                    ),
            ],
        ];
    }
}
