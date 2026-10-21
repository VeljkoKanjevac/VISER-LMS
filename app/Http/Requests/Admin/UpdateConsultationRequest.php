<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $consultation = $this->route('consultation');

        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')
                    ->where(function ($query) use ($consultation) {
                        $query->where('is_active', true)
                            ->orWhere('id', $consultation->course_id);
                    }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'duration_minutes' => [
                'required', 'integer', 'min:1', 'max:480',
            ],
        ];
    }
}
