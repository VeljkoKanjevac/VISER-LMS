<?php

namespace App\Services\Admin;

use App\Models\Lesson;
use App\Repositories\LessonRepository;
use App\Repositories\SectionRepository;
use Illuminate\Database\Eloquent\Collection;

class LessonService
{
    public function __construct(
        private readonly LessonRepository $lessonRepository,
        private readonly SectionRepository $sectionRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->lessonRepository->getAllForAdmin();
    }

    public function getSectionsForCreate(): Collection
    {
        return $this->sectionRepository->getForLessonCreate();
    }

    public function getSectionsForEdit(Lesson $lesson): Collection
    {
        return $this->sectionRepository->getForLessonEdit($lesson->section_id);
    }

    public function create(array $data): Lesson
    {
        return $this->lessonRepository->create($data);
    }

    public function update(Lesson $lesson, array $data): bool
    {
        return $this->lessonRepository->update($lesson, $data);
    }

    public function delete(Lesson $lesson): bool
    {
        if ($this->lessonRepository->hasMaterials($lesson)) {
            return false;
        }

        return $this->lessonRepository->delete($lesson);
    }

    public function togglePublished(Lesson $lesson): bool
    {
        return $this->lessonRepository->togglePublished($lesson);
    }
}
