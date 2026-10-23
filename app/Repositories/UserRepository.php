<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function getAllForAdmin(?string $search = null, ?string $role = null): LengthAwarePaginator {
        return User::query()
            ->withCount([
                'enrollments',
                'payments',
                'referralCode',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();
    }

    public function getForAdminDetails(User $user): User
    {
        return $user->load([
            'enrollments.course',
            'payments',
            'referralCode',
        ]);
    }

    public function count(): int
    {
        return User::query()->count();
    }

    public function countStudents(): int
    {
        return User::query()
            ->where('role', 'student')
            ->count();
    }

    public function getStudentsWithoutReferralCode(): Collection
    {
        return User::query()
            ->where('role', 'student')
            ->whereDoesntHave('referralCode')
            ->orderBy('name')
            ->get();
    }


    public function lockForUpdate(int $userId): ?User
    {
        return User::query()
            ->whereKey($userId)
            ->lockForUpdate()
            ->first();
    }

}
