<?php

namespace App\Services\Admin;

use App\Models\Faculty;
use App\Repositories\FacultyRepository;
use Illuminate\Database\Eloquent\Collection;

class FacultyService
{
    public function __construct(
        private readonly FacultyRepository $facultyRepository
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->facultyRepository->getAllForAdmin();
    }

    public function create(array $data): Faculty
    {
        return $this->facultyRepository->create($data);
    }

    public function update(Faculty $faculty, array $data): bool
    {
        return $this->facultyRepository->update($faculty, $data);
    }

    public function delete(Faculty $faculty): bool
    {
        if ($this->facultyRepository->hasRelatedData($faculty)) {
            return false;
        }

        return $this->facultyRepository->delete($faculty);
    }

    public function toggleActive(Faculty $faculty): bool
    {
        return $this->facultyRepository->toggleActive($faculty);
    }
}
