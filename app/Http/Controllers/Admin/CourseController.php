<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use App\Services\Admin\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService
    ) {}

    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => $this->courseService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.create', [
            'faculties' => $this->courseService->getFacultiesForCreate(),
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $this->courseService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno kreiran.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course,
            'faculties' => $this->courseService
                ->getFacultiesForEdit($course),
        ]);
    }

    public function update(
        UpdateCourseRequest $request,
        Course $course
    ): RedirectResponse {
        $this->courseService->update(
            $course,
            $request->validated()
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno izmenjen.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $deleted = $this->courseService->delete($course);

        if (! $deleted) {
            return redirect()
                ->route('admin.courses.index')
                ->with(
                    'error',
                    'Kurs nije moguće obrisati jer ima povezane podatke. Možete ga deaktivirati.'
                );
        }

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno obrisan.');
    }

    public function toggleActive(Course $course): RedirectResponse
    {
        $this->courseService->toggleActive($course);

        $message = $course->is_active
            ? 'Kurs je uspešno aktiviran.'
            : 'Kurs je uspešno deaktiviran.';

        return redirect()
            ->route('admin.courses.index')
            ->with('success', $message);
    }
}
