<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonRequest;
use App\Http\Requests\Admin\UpdateLessonRequest;
use App\Models\Lesson;
use App\Services\Admin\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(
        private readonly LessonService $lessonService
    ) {}

    public function index(): View
    {
        return view('admin.lessons.index', [
            'lessons' => $this->lessonService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.lessons.create', [
            'sections' => $this->lessonService
                ->getSectionsForCreate(),
        ]);
    }

    public function store(
        StoreLessonRequest $request
    ): RedirectResponse {
        $this->lessonService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.lessons.index')
            ->with(
                'success',
                'Lekcija je uspešno kreirana.'
            );
    }

    public function edit(Lesson $lesson): View
    {
        return view('admin.lessons.edit', [
            'lesson' => $lesson,
            'sections' => $this->lessonService
                ->getSectionsForEdit($lesson),
        ]);
    }

    public function update(
        UpdateLessonRequest $request,
        Lesson $lesson
    ): RedirectResponse {
        $this->lessonService->update(
            $lesson,
            $request->validated()
        );

        return redirect()
            ->route('admin.lessons.index')
            ->with(
                'success',
                'Lekcija je uspešno izmenjena.'
            );
    }

    public function destroy(
        Lesson $lesson
    ): RedirectResponse {
        $deleted = $this->lessonService->delete($lesson);

        if (! $deleted) {
            return redirect()
                ->route('admin.lessons.index')
                ->with(
                    'error',
                    'Lekciju nije moguće obrisati jer ima povezane materijale. Možete je sakriti.'
                );
        }

        return redirect()
            ->route('admin.lessons.index')
            ->with(
                'success',
                'Lekcija je uspešno obrisana.'
            );
    }

    public function togglePublished(
        Lesson $lesson
    ): RedirectResponse {
        $this->lessonService->togglePublished($lesson);

        $message = $lesson->is_published
            ? 'Lekcija je uspešno objavljena.'
            : 'Lekcija je uspešno sakrivena.';

        return redirect()
            ->route('admin.lessons.index')
            ->with('success', $message);
    }
}
