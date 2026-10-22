<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('offers', 'slug'),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'integer',
                'min:0',
            ],

            'referral_discount' => [
                'required',
                'integer',
                'min:0',
                'lte:price',
            ],

            'referral_commission' => [
                'required',
                'integer',
                'min:0',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'course_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'course_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('courses', 'id'),
            ],
        ];
    }
}
