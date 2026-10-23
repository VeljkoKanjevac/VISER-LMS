<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequestRequest;
use App\Models\Offer;
use App\Services\Student\EnrollmentRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EnrollmentRequestController extends Controller
{
    public function __construct(
        private readonly EnrollmentRequestService $enrollmentRequestService
    ) {}

    /**
     * Prikazuje sve zahteve prijavljenog studenta.
     */
    public function index(): View
    {
        $userId = (int) auth()->id();

        $requests = $this->enrollmentRequestService
            ->getStudentRequests($userId);

        return view('student.enrollment-requests.index', [
            'requests' => $requests,
        ]);
    }

    /**
     * Student šalje zahtev za kupovinu ponude.
     */
    public function store(
        StoreEnrollmentRequestRequest $request,
        Offer $offer
    ): RedirectResponse {
        $validated = $request->validated();

        $this->enrollmentRequestService->create(
            userId: (int) $request->user()->id,
            offer: $offer,
            referralCode: $validated['referral_code'] ?? null
        );

        return redirect()
            ->route('student.offers.index')
            ->with(
                'success',
                'Zahtev za upis je uspešno poslat.'
            );
    }
}
