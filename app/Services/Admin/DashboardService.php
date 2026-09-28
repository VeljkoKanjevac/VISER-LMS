<?php

namespace App\Services\Admin;

use App\Repositories\CourseRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\OfferRepository;
use App\Repositories\UserRepository;

class DashboardService
{
    public function __construct(
        private readonly FacultyRepository $facultyRepository,
        private readonly CourseRepository $courseRepository,
        private readonly OfferRepository $offerRepository,
        private readonly UserRepository $userRepository
    ) {}

    public function getStatistics(): array
    {
        return [
            'faculties_count' => $this->facultyRepository->count(),

            'courses_count' => $this->courseRepository->count(),

            'offers_count' => $this->offerRepository->count(),

            'users_count' => $this->userRepository->countStudents(),
        ];
    }
}
