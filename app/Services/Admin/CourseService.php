<?php

namespace App\Services\Admin;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\FacultyRepository;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly FacultyRepository $facultyRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->courseRepository->getAllForAdmin();
    }

    public function getFacultiesForCreate(): Collection
    {
        return $this->facultyRepository->getActive();
    }

    public function getFacultiesForEdit(Course $course): Collection
    {
        return $this->facultyRepository
            ->getActiveOrCurrent($course->faculty_id);
    }

    public function create(array $data): Course
    {
        return $this->courseRepository->create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $this->courseRepository->update($course, $data);
    }

    public function delete(Course $course): bool
    {
        if ($this->courseRepository->hasRelatedData($course)) {
            return false;
        }

        return $this->courseRepository->delete($course);
    }

    public function toggleActive(Course $course): bool
    {
        return $this->courseRepository->toggleActive($course);
    }
}
