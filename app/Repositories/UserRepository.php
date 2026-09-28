<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function countStudents(): int
    {
        return User::query()
            ->where('role', 'student')
            ->count();
    }
}
