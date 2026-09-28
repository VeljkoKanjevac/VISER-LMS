<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with('faculty')
            ->withCount([
                'sections',
                'enrollments',
                'consultations',
                'offers',
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.courses.index', [
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        $faculties = Faculty::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.courses.create', [
            'faculties' => $faculties,
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno kreiran.');
    }

    public function edit(Course $course): View
    {
        $faculties = Faculty::query()
            ->where(function ($query) use ($course) {
                $query
                    ->where('is_active', true)
                    ->orWhere('id', $course->faculty_id);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.courses.edit', [
            'course' => $course,
            'faculties' => $faculties,
        ]);
    }

    public function update(
        UpdateCourseRequest $request,
        Course $course
    ): RedirectResponse {
        $course->update($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno izmenjen.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $hasRelatedData =
            $course->sections()->exists()
            || $course->enrollments()->exists()
            || $course->consultations()->exists()
            || $course->offers()->exists();

        if ($hasRelatedData) {
            return redirect()
                ->route('admin.courses.index')
                ->with(
                    'error',
                    'Kurs nije moguće obrisati jer ima povezane podatke. Možete ga deaktivirati.'
                );
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kurs je uspešno obrisan.');
    }

    public function toggleActive(Course $course): RedirectResponse
    {
        $course->update([
            'is_active' => ! $course->is_active,
        ]);

        $message = $course->is_active
            ? 'Kurs je uspešno aktiviran.'
            : 'Kurs je uspešno deaktiviran.';

        return redirect()
            ->route('admin.courses.index')
            ->with('success', $message);
    }
}
