<?php

namespace App\Services\Admin;

use App\Models\Section;
use App\Repositories\CourseRepository;
use App\Repositories\SectionRepository;
use Illuminate\Database\Eloquent\Collection;

class SectionService
{
    public function __construct(
        private readonly SectionRepository $sectionRepository,
        private readonly CourseRepository $courseRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->sectionRepository->getAllForAdmin();
    }

    public function getCoursesForCreate(): Collection
    {
        return $this->courseRepository->getActive();
    }

    public function getCoursesForEdit(Section $section): Collection
    {
        return $this->courseRepository
            ->getActiveOrCurrent($section->course_id);
    }

    public function create(array $data): Section
    {
        return $this->sectionRepository->create($data);
    }

    public function togglePublished(Section $section): bool
    {
        return $this->sectionRepository->togglePublished($section);
    }

    public function update(
        Section $section,
        array $data
    ): bool {
        return $this->sectionRepository->update(
            $section,
            $data
        );
    }

    public function delete(Section $section): bool
    {
        if ($this->sectionRepository->hasLessons($section)) {
            return false;
        }

        return $this->sectionRepository->delete($section);
    }

}
