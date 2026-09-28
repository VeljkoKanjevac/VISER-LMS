<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Http\Requests\Admin\UpdateSectionRequest;
use App\Models\Section;
use App\Services\Admin\SectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function __construct(
        private readonly SectionService $sectionService
    ) {}

    public function index(): View
    {
        return view('admin.sections.index', [
            'sections' => $this->sectionService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.sections.create', [
            'courses' => $this->sectionService->getCoursesForCreate(),
        ]);
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        $this->sectionService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.sections.index')
            ->with(
                'success',
                'Tematska oblast je uspešno kreirana.'
            );
    }

    public function edit(Section $section): View
    {
        return view('admin.sections.edit', [
            'section' => $section,
            'courses' => $this->sectionService
                ->getCoursesForEdit($section),
        ]);
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        $this->sectionService->update(
            $section,
            $request->validated()
        );

        return redirect()
            ->route('admin.sections.index')
            ->with(
                'success',
                'Tematska oblast je uspešno izmenjena.'
            );
    }

    public function destroy(Section $section): RedirectResponse
    {
        $deleted = $this->sectionService->delete($section);

        if (! $deleted) {
            return redirect()
                ->route('admin.sections.index')
                ->with(
                    'error',
                    'Tematsku oblast nije moguće obrisati jer sadrži lekcije. Možete je sakriti.'
                );
        }

        return redirect()
            ->route('admin.sections.index')
            ->with(
                'success',
                'Tematska oblast je uspešno obrisana.'
            );
    }

    public function togglePublished(Section $section): RedirectResponse
    {
        $this->sectionService->togglePublished($section);

        $message = $section->is_published
            ? 'Tematska oblast je uspešno objavljena.'
            : 'Tematska oblast je uspešno sakrivena.';

        return redirect()
            ->route('admin.sections.index')
            ->with('success', $message);
    }
}
