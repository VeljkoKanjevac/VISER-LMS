<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacultyRequest;
use App\Http\Requests\Admin\UpdateFacultyRequest;
use App\Models\Faculty;
use App\Services\Admin\FacultyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function __construct(
        private readonly FacultyService $facultyService
    ) {}

    public function index(): View
    {
        return view('admin.faculties.index', [
            'faculties' => $this->facultyService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.faculties.create');
    }

    public function store(StoreFacultyRequest $request): RedirectResponse
    {
        $this->facultyService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultet je uspešno kreiran.');
    }

    public function edit(Faculty $faculty): View
    {
        return view('admin.faculties.edit', [
            'faculty' => $faculty,
        ]);
    }

    public function update(UpdateFacultyRequest $request, Faculty $faculty): RedirectResponse
    {
        $this->facultyService->update(
            $faculty,
            $request->validated()
        );

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultet je uspešno izmenjen.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        $deleted = $this->facultyService->delete($faculty);

        if (! $deleted) {
            return redirect()
                ->route('admin.faculties.index')
                ->with(
                    'error',
                    'Fakultet nije moguće obrisati jer ima povezane kurseve ili ponude. Možete ga deaktivirati.'
                );
        }

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultet je uspešno obrisan.');
    }

    public function toggleActive(Faculty $faculty): RedirectResponse
    {
        $this->facultyService->toggleActive($faculty);

        $message = $faculty->is_active
            ? 'Fakultet je uspešno aktiviran.'
            : 'Fakultet je uspešno deaktiviran.';

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', $message);
    }
}
