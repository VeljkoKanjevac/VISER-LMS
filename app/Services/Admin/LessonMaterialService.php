<?php

namespace App\Services\Admin;

use App\Models\LessonMaterial;
use App\Repositories\LessonMaterialRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Repositories\LessonRepository;

class LessonMaterialService
{
    private const DISK = 'lesson_materials';

    public function __construct(
        private readonly LessonMaterialRepository $materialRepository,
        private readonly LessonRepository $lessonRepository
    ) {}

    public function getAllForAdmin(?int $lessonId = null): Collection
    {
        return $this->materialRepository->getAllForAdmin($lessonId);
    }

    public function getLessonsForForm(): Collection
    {
        return $this->lessonRepository->getAllForMaterialForm();
    }

    public function create(array $data, UploadedFile $file): LessonMaterial {
        $path = $file->store('', self::DISK);

        try {
            return $this->materialRepository->create([
                'lesson_id' => $data['lesson_id'],
                'title' => $data['title'],
                'sort_order' => $data['sort_order'],
                'file_path' => $path,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk(self::DISK)->delete($path);

            throw $exception;
        }
    }

    public function update(LessonMaterial $material, array $data): bool {
        return $this->materialRepository->update(
            $material,
            $data
        );
    }

    public function delete(LessonMaterial $material): bool
    {
        $path = $material->file_path;

        $deleted = $this->materialRepository->delete($material);

        if ($deleted) {
            Storage::disk(self::DISK)->delete($path);
        }

        return $deleted;
    }

    public function download(LessonMaterial $material): BinaryFileResponse {
        $filename = Str::slug($material->title) . '.pdf';

        return response()->download(
            Storage::disk(self::DISK)->path($material->file_path),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function getSelectedLesson(?int $lessonId): ?\App\Models\Lesson
    {
        if ($lessonId === null) {
            return null;
        }

        return $this->lessonRepository
            ->findForMaterialManagement($lessonId);
    }
}
