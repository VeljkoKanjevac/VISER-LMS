<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository
{
    public function count(): int
    {
        return Course::count();
    }

    public function getAllForAdmin(): Collection
    {
        return Course::query()
            ->with('faculty')
            ->withCount([
                'sections',
                'enrollments',
                'consultations',
                'offers',
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $course->update($data);
    }

    public function delete(Course $course): bool
    {
        return $course->delete();
    }

    public function hasRelatedData(Course $course): bool
    {
        return $course->sections()->exists()
            || $course->enrollments()->exists()
            || $course->consultations()->exists()
            || $course->offers()->exists();
    }

    public function toggleActive(Course $course): bool
    {
        return $course->update([
            'is_active' => ! $course->is_active,
        ]);
    }

}
