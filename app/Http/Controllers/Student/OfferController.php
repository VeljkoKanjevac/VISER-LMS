<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Admin\OfferService;
use Illuminate\Contracts\View\View;

class OfferController extends Controller
{
    public function __construct(
        private readonly OfferService $offerService
    ) {}


    public function index(): View
    {
        $offers = $this->offerService->getActiveForStudents();

        return view('student.offers.index', [
            'offers' => $offers,
        ]);
    }
}
