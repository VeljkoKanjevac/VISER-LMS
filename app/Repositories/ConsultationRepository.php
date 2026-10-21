<?php

namespace App\Repositories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;

class ConsultationRepository
{
    public function create(array $data): Consultation
    {
        return Consultation::create($data);
    }

    public function update(Consultation $consultation, array $data): bool {
        return $consultation->update($data);
    }

    public function delete(Consultation $consultation): bool
    {
        return $consultation->delete();
    }

    public function getAllForAdmin(): Collection
    {
        return Consultation::query()
            ->with('course.faculty')
            ->orderBy('starts_at')
            ->get();
    }
}
