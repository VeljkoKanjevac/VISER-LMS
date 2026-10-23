<?php

namespace App\Repositories;

use App\Models\ReferralCode;
use Illuminate\Database\Eloquent\Collection;

class ReferralCodeRepository
{
    public function create(array $data): ReferralCode
    {
        return ReferralCode::create($data);
    }

    public function codeExists(string $code): bool
    {
        return ReferralCode::query()
            ->where('code', $code)
            ->exists();
    }

    public function findByUserId(
        int $userId
    ): ?ReferralCode {
        return ReferralCode::query()
            ->where('user_id', $userId)
            ->first();
    }

    public function existsForUser(int $userId): bool
    {
        return ReferralCode::query()
            ->where('user_id', $userId)
            ->exists();
    }

    public function toggleActive(ReferralCode $referralCode): bool
    {
        return $referralCode->update([
            'is_active' => ! $referralCode->is_active,
        ]);
    }

    public function hasCommissions(ReferralCode $referralCode): bool
    {
        return $referralCode->commissions()->exists();
    }

    public function delete(ReferralCode $referralCode): bool
    {
        return $referralCode->delete();
    }

    public function count(): int
    {
        return ReferralCode::query()->count();
    }

    public function getAllForAdmin(): Collection
    {
        return ReferralCode::query()
            ->with('user')
            ->withCount('commissions')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findActiveByCode(string $code): ?ReferralCode {
        return ReferralCode::query()
            ->with('user')
            ->where('code', $code)
            ->where('is_active', true)
            ->first();
    }
}
