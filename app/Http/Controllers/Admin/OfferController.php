<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfferRequest;
use App\Http\Requests\Admin\UpdateOfferRequest;
use App\Models\Offer;
use App\Services\Admin\OfferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function __construct(
        private readonly OfferService $offerService
    ) {}

    public function index(): View
    {
        return view('admin.offers.index', [
            'offers' => $this->offerService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.offers.create', [
            'faculties' => $this->offerService->getFacultiesForCreate(),
            'courses' => $this->offerService->getCoursesForForm(),
        ]);
    }

    public function store(
        StoreOfferRequest $request
    ): RedirectResponse {
        $this->offerService->create($request->validated());

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Ponuda je uspešno kreirana.');
    }

    public function edit(Offer $offer): View
    {
        return view('admin.offers.edit', [
            'offer' => $this->offerService->prepareForEdit($offer),
            'faculties' => $this->offerService->getFacultiesForEdit($offer),
            'courses' => $this->offerService->getCoursesForForm(),
        ]);
    }

    public function update(
        UpdateOfferRequest $request,
        Offer $offer
    ): RedirectResponse {
        $this->offerService->update(
            $offer,
            $request->validated()
        );

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Ponuda je uspešno izmenjena.');
    }

    public function toggleActive(
        Offer $offer
    ): RedirectResponse {
        $this->offerService->toggleActive($offer);

        return redirect()
            ->route('admin.offers.index')
            ->with(
                'success',
                'Status ponude je uspešno promenjen.'
            );
    }

    public function destroy(
        Offer $offer
    ): RedirectResponse {
        $deleted = $this->offerService->delete($offer);

        if (! $deleted) {
            return redirect()
                ->route('admin.offers.index')
                ->with(
                    'error',
                    'Ponuda ima povezane uplate ili referral kodove. Deaktiviraj je umesto brisanja.'
                );
        }

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Ponuda je uspešno obrisana.');
    }
}
