<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReferralCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
                Rule::unique('referral_codes', 'user_id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Izaberi korisnika.',
            'user_id.integer' => 'Izabrani korisnik nije validan.',
            'user_id.exists' => 'Izabrani student ne postoji.',
            'user_id.unique' => 'Ovaj korisnik već ima referral kod.',
        ];
    }
}
