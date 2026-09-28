<?php

namespace App\Repositories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

class SectionRepository
{

    public function create(array $data): Section
    {
        return Section::create($data);
    }

    public function update(Section $section, array $data): bool
    {
        return $section->update($data);
    }

    public function delete(Section $section): bool
    {
        return $section->delete();
    }

    public function hasLessons(Section $section): bool
    {
        return $section->lessons()->exists();
    }

    public function togglePublished(Section $section): bool
    {
        return $section->update([
            'is_published' => ! $section->is_published,
        ]);
    }

    public function getAllForAdmin(): Collection
    {
        return Section::query()
            ->with('course.faculty')
            ->withCount('lessons')
            ->orderBy('course_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }
}
