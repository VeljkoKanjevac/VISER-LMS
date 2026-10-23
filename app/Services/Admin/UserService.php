<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function getAllForAdmin(?string $search = null, ?string $role = null): LengthAwarePaginator {
        return $this->userRepository->getAllForAdmin($search, $role);
    }

    public function getForAdminDetails(User $user): User
    {
        return $this->userRepository->getForAdminDetails($user);
    }

    public function getStatistics(): array
    {
        return [
            'total_users' => $this->userRepository->count(),
            'total_students' => $this->userRepository->countStudents(),
        ];
    }
}
