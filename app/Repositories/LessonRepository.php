<?php

namespace App\Repositories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository
{
    public function create(array $data): Lesson
    {
        return Lesson::create($data);
    }

    public function update(Lesson $lesson, array $data): bool
    {
        return $lesson->update($data);
    }

    public function delete(Lesson $lesson): bool
    {
        return $lesson->delete();
    }

    public function hasMaterials(Lesson $lesson): bool
    {
        return $lesson->materials()->exists();
    }

    public function togglePublished(Lesson $lesson): bool
    {
        return $lesson->update([
            'is_published' => ! $lesson->is_published,
        ]);
    }

    public function getAllForAdmin(): Collection
    {
        return Lesson::query()
            ->with(['section.course.faculty',])
            ->withCount('materials')
            ->orderBy('section_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }
}
