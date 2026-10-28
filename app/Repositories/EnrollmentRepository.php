<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository
{
    public function create(array $data): Enrollment
    {
        return Enrollment::create($data);
    }

    public function existsForUserAndCourse(int $userId, int $courseId): bool {
        return Enrollment::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }
}
