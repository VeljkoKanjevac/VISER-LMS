<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lesson = $this->route('lesson');

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

            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')
                    ->where(function ($query) use ($lesson) {
                        $query
                            ->whereIn(
                                'course_id',
                                \App\Models\Course::query()
                                    ->where('is_active', true)
                                    ->select('id')
                            )
                            ->orWhere('id', $lesson->section_id);
                    }),
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
                    )
                    ->ignore($lesson->id),
            ],
        ];
    }
}
