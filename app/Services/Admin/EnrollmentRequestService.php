<?php

namespace App\Services\Admin;

use App\Repositories\EnrollmentRequestRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\PaymentRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\ReferralCommissionRepository;
use Illuminate\Support\Facades\DB;

class EnrollmentRequestService
{
    public function __construct(
        private EnrollmentRequestRepository $enrollmentRequestRepository,
        private PaymentRepository $paymentRepository,
        private EnrollmentRepository $enrollmentRepository,
        private ReferralCommissionRepository $referralCommissionRepository,
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->enrollmentRequestRepository
            ->getAllForAdmin();
    }


    public function reject(int $enrollmentRequestId): void
    {
        DB::transaction(function () use ($enrollmentRequestId) {

            $enrollmentRequest = $this->enrollmentRequestRepository
                ->findForUpdate($enrollmentRequestId);

            if (!$enrollmentRequest) {
                throw ValidationException::withMessages([
                    'request' => 'Zahtev za upis nije pronađen.',
                ]);
            }

            if (!$enrollmentRequest->isPending()) {
                throw ValidationException::withMessages([
                    'request' => 'Samo zahtevi na čekanju mogu biti odbijeni.',
                ]);
            }

            $rejected = $this->enrollmentRequestRepository
                ->reject($enrollmentRequest);

            if (!$rejected) {
                throw new \RuntimeException(
                    'Došlo je do greške prilikom odbijanja zahteva.'
                );
            }
        });
    }



    public function confirm(int $enrollmentRequestId): void
    {
        DB::transaction(function () use ($enrollmentRequestId) {
            
            $enrollmentRequest = $this->enrollmentRequestRepository
                ->findForUpdate($enrollmentRequestId);

            if (!$enrollmentRequest) {
                throw ValidationException::withMessages([
                    'request' => 'Zahtev za upis nije pronađen.',
                ]);
            }

            if (!$enrollmentRequest->isPending()) {
                throw ValidationException::withMessages([
                    'request' => 'Samo zahtevi na čekanju mogu biti potvrđeni.',
                ]);
            }

            $courses = $enrollmentRequest->offer?->courses;

            if (!$courses || $courses->isEmpty()) {
                throw ValidationException::withMessages([
                    'request' => 'Ponuda ne sadrži kurseve.',
                ]);
            }

            $courseIds = $courses->pluck('id')->all();

            if ($this->enrollmentRequestRepository->hasExistingEnrollment(
                $enrollmentRequest->user_id,
                $courseIds
            )) {
                throw ValidationException::withMessages([
                    'request' => 'Student već ima pristup nekom od kurseva iz ponude.',
                ]);
            }

            $payment = $this->paymentRepository->create([
                'user_id' => $enrollmentRequest->user_id,
                'offer_id' => $enrollmentRequest->offer_id,
                'regular_amount' => $enrollmentRequest->regular_amount,
                'discount_amount' => $enrollmentRequest->discount_amount,
                'amount' => $enrollmentRequest->amount,
                'note' => 'Potvrđen zahtev #' . $enrollmentRequest->id,
                'paid_at' => now(),
            ]);

            foreach ($courses as $course) {
                $this->enrollmentRepository->create([
                    'user_id' => $enrollmentRequest->user_id,
                    'course_id' => $course->id,
                    'enrolled_at' => now(),
                ]);
            }

            if (
                $enrollmentRequest->referral_code_id &&
                $enrollmentRequest->referral_commission_amount > 0
            ) {
                $this->referralCommissionRepository->create([
                    'referral_code_id' => $enrollmentRequest->referral_code_id,
                    'referred_user_id' => $enrollmentRequest->user_id,
                    'payment_id' => $payment->id,
                    'amount' => $enrollmentRequest->referral_commission_amount,
                    'status' => 'earned',
                    'paid_at' => null,
                ]);
            }

            $confirmed = $this->enrollmentRequestRepository
                ->confirm($enrollmentRequest);

            if (!$confirmed) {
                throw new \RuntimeException(
                    'Potvrđivanje zahteva nije uspelo.'
                );
            }

        }, 3);
    }

}
