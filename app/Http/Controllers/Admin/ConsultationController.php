<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConsultationRequest;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Http\Requests\Admin\UpdateConsultationLinkRequest;
use App\Models\Consultation;
use App\Services\Admin\ConsultationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function __construct(
        private readonly ConsultationService $consultationService
    ) {}

    public function index(): View
    {
        return view('admin.consultations.index', [
            'consultations' => $this->consultationService
                ->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.consultations.create', [
            'courses' => $this->consultationService
                ->getCoursesForCreate(),
        ]);
    }

    public function store(StoreConsultationRequest $request): RedirectResponse {
        $this->consultationService->create($request->validated());

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Konsultacija je zakazana.');
    }

    public function edit(Consultation $consultation): View
    {
        return view('admin.consultations.edit', [
            'consultation' => $consultation,
            'courses' => $this->consultationService
                ->getCoursesForEdit($consultation),
        ]);
    }

    public function update(UpdateConsultationRequest $request, Consultation $consultation): RedirectResponse {
        $this->consultationService->update(
            $consultation,
            $request->validated()
        );

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Konsultacija je izmenjena.');
    }

    public function updateLink(UpdateConsultationLinkRequest $request, Consultation $consultation): RedirectResponse {
        $this->consultationService->updateLink(
            $consultation,
            $request->validated('meeting_url')
        );

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Link je sačuvan.');
    }

    public function toggleActive(Consultation $consultation): RedirectResponse {
        $this->consultationService->toggleActive($consultation);

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Status konsultacije je promenjen.');
    }

    public function destroy(Consultation $consultation): RedirectResponse {
        $this->consultationService->delete($consultation);

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Konsultacija je obrisana.');
    }
}
