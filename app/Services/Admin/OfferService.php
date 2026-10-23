<?php

namespace App\Services\Admin;

use App\Models\Offer;
use App\Repositories\CourseRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\OfferRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OfferService
{
    public function __construct(
        private readonly OfferRepository $offerRepository,
        private readonly CourseRepository $courseRepository,
        private readonly FacultyRepository $facultyRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->offerRepository->getAllForAdmin();
    }

    public function getFacultiesForCreate(): Collection
    {
        return $this->facultyRepository->getActive();
    }

    public function getFacultiesForEdit(Offer $offer): Collection
    {
        return $this->facultyRepository->getActiveOrCurrent(
            $offer->faculty_id
        );
    }

    public function getCoursesForForm(): Collection
    {
        return $this->courseRepository->getForOfferForm();
    }

    public function prepareForEdit(Offer $offer): Offer
    {
        return $this->offerRepository->loadCourses($offer);
    }

    public function getActiveForStudents(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->offerRepository->getActiveForStudents();
    }

    public function create(array $data): Offer
    {
        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        $this->validateCourses(
            $courseIds,
            (int) $data['faculty_id']
        );

        // Nova ponuda je neaktivna dok je administrator ne aktivira.
        $data['is_active'] = false;

        return DB::transaction(function () use ($data, $courseIds) {
            $offer = $this->offerRepository->create($data);

            $this->offerRepository->syncCourses($offer, $courseIds);

            return $offer;
        });
    }

    public function update(Offer $offer, array $data): bool
    {
        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        $this->validateCourses(
            $courseIds,
            (int) $data['faculty_id']
        );

        if ((int) $data['faculty_id'] !== (int) $offer->faculty_id
            && $this->offerRepository->hasPayments($offer)) {
            throw ValidationException::withMessages([
                'faculty_id' =>
                    'Fakultet ponude sa evidentiranim uplatama ne može se menjati.',
            ]);
        }

        if ($this->offerRepository->hasPayments($offer)) {
            $existingCourseIds = $this->offerRepository
                ->getCourseIds($offer);

            $newCourseIds = array_map('intval', $courseIds);

            sort($existingCourseIds);
            sort($newCourseIds);

            if ($existingCourseIds !== $newCourseIds) {
                throw ValidationException::withMessages([
                    'course_ids' =>
                        'Kursevi ponude sa evidentiranim uplatama ne mogu se menjati.',
                ]);
            }
        }

        return DB::transaction(function () use (
            $offer,
            $data,
            $courseIds
        ) {
            $updated = $this->offerRepository->update(
                $offer,
                $data
            );

            $this->offerRepository->syncCourses(
                $offer,
                $courseIds
            );

            return $updated;
        });
    }

    public function toggleActive(Offer $offer): bool
    {
        return $this->offerRepository->toggleActive($offer);
    }

    public function delete(Offer $offer): bool
    {
        if ($this->offerRepository->hasRelatedData($offer)) {
            return false;
        }

        return $this->offerRepository->delete($offer);
    }

    private function validateCourses(array $courseIds, int $facultyId): void {
        $uniqueIds = array_unique(array_map('intval', $courseIds));

        if (count($uniqueIds) === 0
            || $this->courseRepository->countMatchingFaculty($uniqueIds, $facultyId) !== count($uniqueIds)) {
            throw ValidationException::withMessages([
                'course_ids' =>
                    'Svi izabrani kursevi moraju pripadati fakultetu ponude.',
            ]);
        }
    }
}
