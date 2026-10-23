<?php

namespace App\Repositories;

use App\Models\EnrollmentRequest;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRequestRepository
{

    public function getAllForAdmin(): Collection
    {
        return EnrollmentRequest::query()
            ->with([
                'user',
                'offer',
                'referralCode.user',
            ])
            ->orderByRaw(
                "CASE WHEN status = 'pending' THEN 0 ELSE 1 END"
            )
            ->orderByDesc('created_at')
            ->get();
    }

    public function getPendingForAdmin(): Collection
    {
        return EnrollmentRequest::query()
            ->with([
                'user',
                'offer',
                'referralCode.user',
            ])
            ->where(
                'status',
                EnrollmentRequest::STATUS_PENDING
            )
            ->latest()
            ->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return EnrollmentRequest::query()
            ->with([
                'offer',
                'referralCode',
            ])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data): EnrollmentRequest
    {
        return EnrollmentRequest::create($data);
    }

    public function getForAdminDetails(
        EnrollmentRequest $enrollmentRequest
    ): EnrollmentRequest {
        return $enrollmentRequest->load([
            'user',
            'offer',
            'referralCode.user',
        ]);
    }

    public function findForUpdate(int $id): ?EnrollmentRequest
    {
        return EnrollmentRequest::query()
            ->whereKey($id)
            ->lockForUpdate()
            ->first();
    }


    public function hasPendingRequest(int $userId, int $offerId): bool {
        return EnrollmentRequest::query()
            ->where('user_id', $userId)
            ->where('offer_id', $offerId)
            ->where(
                'status',
                EnrollmentRequest::STATUS_PENDING
            )
            ->exists();
    }


    public function confirm(EnrollmentRequest $enrollmentRequest): bool {
        return $enrollmentRequest->update([
            'status' => EnrollmentRequest::STATUS_CONFIRMED,
            'confirmed_at' => now(),
            'rejected_at' => null,
        ]);
    }


    public function reject(EnrollmentRequest $enrollmentRequest, ?string $adminNote = null): bool {
        return $enrollmentRequest->update([
            'status' => EnrollmentRequest::STATUS_REJECTED,
            'admin_note' => $adminNote,
            'rejected_at' => now(),
            'confirmed_at' => null,
        ]);
    }

    public function countPending(): int
    {
        return EnrollmentRequest::query()
            ->where(
                'status',
                EnrollmentRequest::STATUS_PENDING
            )
            ->count();
    }
}
