<?php

namespace App\Services\Admin;

use App\Models\Consultation;
use App\Repositories\ConsultationRepository;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ConsultationService
{
    public function __construct(
        private readonly ConsultationRepository $consultationRepository,
        private readonly CourseRepository $courseRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->consultationRepository->getAllForAdmin();
    }

    public function getCoursesForCreate(): Collection
    {
        return $this->courseRepository->getActive();
    }

    public function getCoursesForEdit(
        Consultation $consultation
    ): Collection {
        return $this->courseRepository->getActiveOrCurrent(
            $consultation->course_id
        );
    }

    public function create(array $data): Consultation
    {
        return $this->consultationRepository->create(
            $this->prepareData($data)
        );
    }

    public function update(Consultation $consultation, array $data): bool {
        return $this->consultationRepository->update(
            $consultation,
            $this->prepareData($data)
        );
    }

    public function updateLink(Consultation $consultation, ?string $meetingUrl): bool {
        return $this->consultationRepository->update(
            $consultation,
            ['meeting_url' => $meetingUrl]
        );
    }

    public function toggleActive(Consultation $consultation): bool {
        return $this->consultationRepository->update(
            $consultation,
            ['is_active' => ! $consultation->is_active]
        );
    }

    public function delete(Consultation $consultation): bool
    {
        return $this->consultationRepository->delete($consultation);
    }

    private function prepareData(array $data): array
    {
        $startsAt = Carbon::parse($data['starts_at']);

        $data['starts_at'] = $startsAt;
        $data['ends_at'] = $startsAt->copy()->addMinutes(
            (int) $data['duration_minutes']
        );

        unset($data['duration_minutes']);

        return $data;
    }
}
