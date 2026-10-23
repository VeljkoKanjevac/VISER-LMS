<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReferralCodeRequest;
use App\Models\ReferralCode;
use App\Services\Admin\ReferralCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReferralCodeController extends Controller
{
    public function __construct(
        private readonly ReferralCodeService $referralCodeService
    ) {}

    public function index(): View
    {
        return view('admin.referral-codes.index', [
            'referralCodes' => $this->referralCodeService->getAllForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.referral-codes.create', [
            'users' => $this->referralCodeService->getUsersForCreate(),
        ]);
    }

    public function store(StoreReferralCodeRequest $request): RedirectResponse {
        $this->referralCodeService->create(
            (int) $request->validated('user_id')
        );

        return redirect()
            ->route('admin.referral-codes.index')
            ->with('success', 'Referral kod je uspešno kreiran.');
    }

    public function toggleActive(ReferralCode $referralCode): RedirectResponse {
        $this->referralCodeService->toggleActive($referralCode);

        return redirect()
            ->route('admin.referral-codes.index')
            ->with('success', 'Status referral koda je promenjen.');
    }

    public function destroy(ReferralCode $referralCode): RedirectResponse {
        if (! $this->referralCodeService->delete($referralCode)) {
            return redirect()
                ->route('admin.referral-codes.index')
                ->with(
                    'error',
                    'Referral kod sa evidentiranim provizijama ne može se obrisati.'
                );
        }

        return redirect()
            ->route('admin.referral-codes.index')
            ->with('success', 'Referral kod je uspešno obrisan.');
    }
}
