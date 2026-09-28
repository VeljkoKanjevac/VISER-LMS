<?php

namespace App\Services\Admin;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Offer;
use App\Models\User;

class DashboardService
{
    public function getStatistics(): array
    {
        return [
            'users_count' => User::where('role', 'student')->count(),
            'faculties_count' => Faculty::count(),
            'courses_count' => Course::count(),
            'offers_count' => Offer::count(),
        ];
    }
}
