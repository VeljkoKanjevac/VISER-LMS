<?php

namespace App\Repositories;

use App\Models\LessonMaterial;
use Illuminate\Database\Eloquent\Collection;

class LessonMaterialRepository
{
    public function create(array $data): LessonMaterial
    {
        return LessonMaterial::create($data);
    }

    public function update(
        LessonMaterial $material,
        array $data
    ): bool {
        return $material->update($data);
    }

    public function delete(LessonMaterial $material): bool
    {
        return $material->delete();
    }

    public function getAllForAdmin(?int $lessonId = null): Collection
    {
        return LessonMaterial::query()
            ->with('lesson.section.course.faculty')
            ->when(
                $lessonId !== null,
                fn ($query) => $query->where('lesson_id', $lessonId)
            )
            ->orderBy('lesson_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }


}
