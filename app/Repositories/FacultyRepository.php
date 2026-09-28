<?php

namespace App\Repositories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Collection;

class FacultyRepository
{

    public function count(): int
    {
        return Faculty::count();
    }

    public function create(array $data): Faculty
    {
        return Faculty::create($data);
    }

    public function update(Faculty $faculty, array $data): bool
    {
        return $faculty->update($data);
    }

    public function delete(Faculty $faculty): bool
    {
        return $faculty->delete();
    }

    public function hasRelatedData(Faculty $faculty): bool
    {
        return $faculty->courses()->exists()
            || $faculty->offers()->exists();
    }

    public function toggleActive(Faculty $faculty): bool
    {
        return $faculty->update([
            'is_active' => ! $faculty->is_active,
        ]);
    }

    public function getAllForAdmin(): Collection
    {
        return Faculty::query()
            ->withCount(['courses', 'offers'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getActive(): Collection
    {
        return Faculty::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getActiveOrCurrent(int $facultyId): Collection
    {
        return Faculty::query()
            ->where(function ($query) use ($facultyId) {
                $query
                    ->where('is_active', true)
                    ->orWhere('id', $facultyId);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
