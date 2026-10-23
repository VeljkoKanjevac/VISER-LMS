<?php

namespace App\Services\Student;

use App\Models\EnrollmentRequest;
use App\Models\Offer;
use App\Repositories\EnrollmentRequestRepository;
use App\Repositories\ReferralCodeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class EnrollmentRequestService
{
    public function __construct(
        private readonly EnrollmentRequestRepository $enrollmentRequestRepository,
        private readonly ReferralCodeRepository $referralCodeRepository,
        private readonly UserRepository $userRepository
    ) {}


    public function getStudentRequests(int $userId): Collection
    {
        return $this->enrollmentRequestRepository
            ->getByUserId($userId);
    }

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
            /*
             * Zaključavamo red korisnika kako bi zahtevi
             * istog studenta bili obrađeni jedan po jedan.
             *
             * Ovo zaključavanje koristićemo i prilikom
             * potvrđivanja uplata.
             */
            $lockedUser = $this->userRepository
                ->lockForUpdate($userId);

            if (!$lockedUser) {
                throw ValidationException::withMessages([
                    'user_id' => 'Korisnik ne postoji.',
                ]);
            }

            /*
             * 1. Provera aktivnosti ponude
             */
            $offer->refresh();

            if (!$offer->is_active) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Izabrana ponuda trenutno nije dostupna.',
                ]);
            }

            /*
             * 2. Učitavanje kurseva iz ponude
             */
            $offer->load('courses');

            $courseIds = $offer->courses
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            if (empty($courseIds)) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Izabrana ponuda nema dodeljene kurseve.',
                ]);
            }

            /*
             * 3. Provera postojećeg zahteva za istu ponudu
             */
            if ($this->enrollmentRequestRepository->hasPendingRequest(
                $userId,
                $offer->id
            )) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Već imaš zahtev na čekanju za ovu ponudu.',
                ]);
            }

            /*
             * 4. Provera već plaćene ponude
             */
            if ($this->enrollmentRequestRepository->hasConfirmedPayment(
                $userId,
                $offer->id
            )) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Već si kupio ovu ponudu.',
                ]);
            }

            /*
             * 5. Provera postojećih upisa na kurseve
             */
            if ($this->enrollmentRequestRepository->hasExistingEnrollment(
                $userId,
                $courseIds
            )) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Već imaš pristup jednom ili više kurseva iz ove ponude.',
                ]);
            }

            /*
             * 6. Provera zahteva koji se preklapaju
             */
            if ($this->enrollmentRequestRepository->hasOverlappingPendingRequest(
                $userId,
                $courseIds
            )) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Već imaš zahtev na čekanju za neki od kurseva iz ove ponude.',
                ]);
            }

            /*
             * 7. Obračun cene
             */
            $regularAmount = (int) $offer->price;

            if ($regularAmount <= 0) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Cena ponude nije ispravna.',
                ]);
            }

            $discountAmount = 0;
            $commissionAmount = 0;
            $referralCodeId = null;

            /*
             * 8. Provera referral koda
             */
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

            /*
             * 9. Validacija iznosa
             */
            if (
                $discountAmount < 0 ||
                $discountAmount > $regularAmount ||
                $commissionAmount < 0
            ) {
                throw ValidationException::withMessages([
                    'offer_id' => 'Referral uslovi ponude nisu ispravni.',
                ]);
            }

            /*
             * 10. Konačna cena
             */
            $amount = $regularAmount - $discountAmount;

            /*
             * 11. Kreiranje zahteva
             */
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
