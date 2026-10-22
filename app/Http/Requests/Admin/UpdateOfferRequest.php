<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $offer = $this->route('offer');

        return [
            'faculty_id' => [
                'required',
                'integer',
                Rule::exists('faculties', 'id')
                    ->where(function ($query) use ($offer) {
                        $query->where('is_active', true)
                            ->orWhere('id', $offer->faculty_id);
                    }),
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
                Rule::unique('offers', 'slug')
                    ->ignore($offer->id),
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
