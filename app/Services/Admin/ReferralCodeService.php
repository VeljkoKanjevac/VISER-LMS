<?php

namespace App\Services\Admin;

use App\Models\ReferralCode;
use App\Repositories\ReferralCodeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Repositories\UserRepository;

class ReferralCodeService
{
    public function __construct(
        private readonly ReferralCodeRepository $referralCodeRepository,
        private readonly UserRepository $userRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->referralCodeRepository->getAllForAdmin();
    }

    public function toggleActive(ReferralCode $referralCode): bool
    {
        return $this->referralCodeRepository->toggleActive($referralCode);
    }

    public function delete(ReferralCode $referralCode): bool
    {
        if ($this->referralCodeRepository->hasCommissions($referralCode)) {
            return false;
        }

        return $this->referralCodeRepository->delete($referralCode);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = 'REF-' . Str::upper(Str::random(8));
        } while ($this->referralCodeRepository->codeExists($code));

        return $code;
    }

    public function create(int $userId): ReferralCode
    {
        if ($this->referralCodeRepository->existsForUser($userId)) {
            throw ValidationException::withMessages([
                'user_id' => 'Korisnik već ima referral kod.',
            ]);
        }

        $code = $this->generateUniqueCode();

        return $this->referralCodeRepository->create([
            'user_id' => $userId,
            'code' => $code,
            'is_active' => true,
        ]);
    }

    public function getUsersForCreate(): Collection
    {
        return $this->userRepository
            ->getStudentsWithoutReferralCode();
    }
}
