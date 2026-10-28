<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreEnrollmentRequest;
use App\Models\Offer;
use App\Services\Student\EnrollmentRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EnrollmentRequestController extends Controller
{
    public function __construct(
        private readonly EnrollmentRequestService $enrollmentRequestService
    ) {}

    public function index(): View
    {
        $userId = (int) auth()->id();

        $requests = $this->enrollmentRequestService
            ->getStudentRequests($userId);

        return view('student.enrollment-requests.index', [
            'requests' => $requests,
        ]);
    }


    public function store(
        StoreEnrollmentRequest $request,
        Offer                  $offer
    ): RedirectResponse {
        $validated = $request->validated();

        $this->enrollmentRequestService->create(
            userId: (int) $request->user()->id,
            offer: $offer,
            referralCode: $validated['referral_code'] ?? null
        );

        return redirect()
            ->route('student.enrollment-requests.index')
            ->with(
                'success',
                'Zahtev za upis je uspešno poslat.'
            );
    }
}
