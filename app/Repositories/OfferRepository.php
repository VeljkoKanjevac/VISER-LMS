<?php

namespace App\Repositories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

class OfferRepository
{
    public function count(): int
    {
        return Offer::query()->count();
    }

    public function loadCourses(Offer $offer): Offer
    {
        return $offer->load('courses');
    }

    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update(Offer $offer, array $data): bool
    {
        return $offer->update($data);
    }

    public function syncCourses(Offer $offer, array $courseIds): void
    {
        $offer->courses()->sync($courseIds);
    }

    public function hasRelatedData(Offer $offer): bool
    {
        return $offer->payments()->exists();
    }

    public function hasPayments(Offer $offer): bool
    {
        return $offer->payments()->exists();
    }

    public function getCourseIds(Offer $offer): array
    {
        return $offer->courses()
            ->pluck('courses.id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function delete(Offer $offer): bool
    {
        return $offer->delete();
    }

    public function toggleActive(Offer $offer): bool
    {
        return $offer->update([
            'is_active' => ! $offer->is_active,
        ]);
    }

    public function getAllForAdmin(): Collection
    {
        return Offer::query()
            ->with(['faculty', 'courses'])
            ->withCount('payments')
            ->orderBy('faculty_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getActiveForStudents(): Collection
    {
        return Offer::query()
            ->with([
                'faculty',
                'courses',
            ])
            ->where('is_active', true)
            ->whereHas('courses')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }



}
