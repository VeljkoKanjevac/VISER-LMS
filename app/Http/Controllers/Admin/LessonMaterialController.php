<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonMaterialRequest;
use App\Http\Requests\Admin\UpdateLessonMaterialRequest;
use App\Models\LessonMaterial;
use App\Services\Admin\LessonMaterialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;

class LessonMaterialController extends Controller
{
    public function __construct(
        private readonly LessonMaterialService $materialService
    ) {}

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
        ]);

        $lessonId = isset($validated['lesson_id'])
            ? (int) $validated['lesson_id']
            : null;

        return view('admin.lesson-materials.index', [
            'materials' => $this->materialService->getAllForAdmin($lessonId),
            'lessons' => $this->materialService->getLessonsForForm(),
            'selectedLesson' => $this->materialService->getSelectedLesson($lessonId),
            'selectedLessonId' => $lessonId,
        ]);
    }

    public function store(
        StoreLessonMaterialRequest $request
    ): RedirectResponse {
        $this->materialService->create(
            $request->safe()->except('file'),
            $request->file('file')
        );

        return redirect()
            ->route('admin.lesson-materials.index', [
                'lesson_id' => $request->validated('lesson_id'),
            ])
            ->with('success', 'Materijal je uspešno dodat.');
    }

    public function update(
        UpdateLessonMaterialRequest $request,
        LessonMaterial $lessonMaterial
    ): RedirectResponse {
        $this->materialService->update(
            $lessonMaterial,
            $request->validated()
        );

        return redirect()
            ->route('admin.lesson-materials.index', [
                'lesson_id' => $request->validated('lesson_id'),
            ])
            ->with('success', 'Materijal je uspešno izmenjen.');
    }

    public function destroy(LessonMaterial $lessonMaterial): RedirectResponse {

        $lessonId = $lessonMaterial->lesson_id;

        $this->materialService->delete($lessonMaterial);

        return redirect()
            ->route('admin.lesson-materials.index', [
                'lesson_id' => $lessonId,
            ])
            ->with('success', 'Materijal je uspešno obrisan.');
    }

    public function download(
        LessonMaterial $lessonMaterial
    ): BinaryFileResponse {
        return $this->materialService->download($lessonMaterial);
    }
}
