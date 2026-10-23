<?php

namespace App\Http\Requests;

use App\Models\Offer;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequestRequest extends FormRequest
{
    /**
     * Samo prijavljeni studenti mogu slati zahteve.
     */
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->role === 'student';
    }

    /**
     * Pravila validacije.
     *
     * Ponudu preuzimamo iz URL-a, a ne iz forme.
     */
    public function rules(): array
    {
        return [
            'referral_code' => [
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    /**
     * Dodatna validacija ponude iz URL-a.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $offer = $this->route('offer');

            if (!$offer instanceof Offer || !$offer->is_active) {
                $validator->errors()->add(
                    'offer_id',
                    'Izabrana ponuda ne postoji ili nije aktivna.'
                );
            }
        });
    }

    /**
     * Prilagođene poruke.
     */
    public function messages(): array
    {
        return [
            'referral_code.string' =>
                'Referral kod mora biti tekst.',

            'referral_code.max' =>
                'Referral kod ne može imati više od 50 karaktera.',
        ];
    }

    /**
     * Normalizacija referral koda.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('referral_code'))) {
            $this->merge([
                'referral_code' => strtoupper(
                    trim($this->input('referral_code'))
                ),
            ]);
        }
    }
}
