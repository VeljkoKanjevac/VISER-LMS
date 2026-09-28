<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacultyRequest;
use App\Http\Requests\Admin\UpdateFacultyRequest;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function index(): View
    {
        $faculties = Faculty::query()
            ->withCount(['courses', 'offers'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.faculties.index', [
            'faculties' => $faculties,
        ]);
    }

    public function create(): View
    {
        return view('admin.faculties.create');
    }

    public function store(StoreFacultyRequest $request): RedirectResponse
    {
        Faculty::create($request->validated());

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

    public function update(
        UpdateFacultyRequest $request,
        Faculty $faculty
    ): RedirectResponse {
        $faculty->update($request->validated());

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultet je uspešno izmenjen.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        if ($faculty->courses()->exists() || $faculty->offers()->exists()) {
            return redirect()
                ->route('admin.faculties.index')
                ->with(
                    'error',
                    'Fakultet nije moguće obrisati jer ima povezane kurseve ili ponude. Možete ga deaktivirati.'
                );
        }

        $faculty->delete();

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultet je uspešno obrisan.');
    }

    public function toggleActive(Faculty $faculty): RedirectResponse
    {
        $faculty->update([
            'is_active' => ! $faculty->is_active,
        ]);

        $message = $faculty->is_active
            ? 'Fakultet je uspešno aktiviran.'
            : 'Fakultet je uspešno deaktiviran.';

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', $message);
    }
}
