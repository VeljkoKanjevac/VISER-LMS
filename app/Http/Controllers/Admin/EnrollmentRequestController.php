<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentRequest;
use App\Services\Admin\EnrollmentRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EnrollmentRequestController extends Controller
{
    public function __construct(
        private EnrollmentRequestService $enrollmentRequestService
    ) {}

    public function index(): View
    {
        $requests = $this->enrollmentRequestService
            ->getAllForAdmin();

        return view('admin.enrollment-requests.index', [
            'requests' => $requests,
        ]);
    }

    public function confirm(
        EnrollmentRequest $enrollmentRequest
    ): RedirectResponse {
        $this->enrollmentRequestService
            ->confirm($enrollmentRequest->id);

        return redirect()
            ->route('admin.enrollment-requests.index')
            ->with('success', 'Uplata je uspešno potvrđena.');
    }

    public function reject(
        EnrollmentRequest $enrollmentRequest
    ): RedirectResponse {
        $this->enrollmentRequestService
            ->reject($enrollmentRequest->id);

        return redirect()
            ->route('admin.enrollment-requests.index')
            ->with('success', 'Zahtev je uspešno odbijen.');
    }
}
