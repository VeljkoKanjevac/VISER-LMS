<?php

namespace App\Services\Admin;

use App\Models\EnrollmentRequest;
use App\Models\Offer;
use App\Repositories\EnrollmentRequestRepository;
use App\Repositories\ReferralCodeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentRequestService
{
    public function __construct(
        private readonly EnrollmentRequestRepository $enrollmentRequestRepository,
        private readonly ReferralCodeRepository $referralCodeRepository
    ) {}


    public function create(
        int $userId,
        Offer $offer,
        ?string $referralCode = null
    ): EnrollmentRequest {
        return DB::transaction(function () use (
            $userId,
            $offer,
            $referralCode
        ) {
            if ($this->enrollmentRequestRepository->hasPendingRequest(
                $userId,
                $offer->id
            )) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Već imaš zahtev na čekanju za ovu ponudu.',
                ]);
            }

            $regularAmount = (int) $offer->price;

            if ($regularAmount <= 0) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Cena ponude nije ispravna.',
                ]);
            }

            $discountAmount = 0;
            $commissionAmount = 0;
            $referralCodeId = null;

            $referralCode = trim($referralCode ?? '');

            if ($referralCode !== '') {
                $referral = $this->referralCodeRepository
                    ->findActiveByCode($referralCode);

                if (!$referral) {
                    throw ValidationException::withMessages([
                        'referral_code' => 'Referral kod nije validan ili nije aktivan.',
                    ]);
                }

                if ((int) $referral->user_id === $userId) {
                    throw ValidationException::withMessages([
                        'referral_code' => 'Ne možeš koristiti sopstveni referral kod.',
                    ]);
                }

                $discountAmount = (int) $offer->referral_discount;
                $commissionAmount = (int) $offer->referral_commission;
                $referralCodeId = $referral->id;
            }

            if (
                $discountAmount < 0 ||
                $discountAmount > $regularAmount ||
                $commissionAmount < 0
            ) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Referral uslovi ponude nisu ispravni.',
                ]);
            }


            $amount = $regularAmount - $discountAmount;

            return $this->enrollmentRequestRepository->create([
                'user_id' => $userId,
                'offer_id' => $offer->id,
                'referral_code_id' => $referralCodeId,
                'regular_amount' => $regularAmount,
                'discount_amount' => $discountAmount,
                'amount' => $amount,
                'referral_commission_amount' => $commissionAmount,
                'status' => EnrollmentRequest::STATUS_PENDING,
            ]);
        });
    }
}
