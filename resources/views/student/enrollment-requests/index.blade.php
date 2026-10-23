
@extends('layouts.student')

@section('title', 'Moji zahtevi')

@section('page-title', 'Moji zahtevi za upis')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <p class="text-muted mb-0">
            Prati status svojih zahteva i pregledaj podatke za uplatu.
        </p>

        <a href="{{ route('student.offers.index') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Pregled ponuda
        </a>
    </div>

    @if($requests->isEmpty())

        <div class="student-card p-5 text-center">
            <i class="bi bi-file-earmark-text text-secondary"
               style="font-size: 3rem;"></i>

            <h5 class="fw-bold mt-3">
                Još nemaš zahteve za upis
            </h5>

            <p class="text-muted">
                Izaberi ponudu i pošalji svoj prvi zahtev.
            </p>

            <a href="{{ route('student.offers.index') }}"
               class="btn btn-primary mt-2">
                Pogledaj ponude
            </a>
        </div>

    @else

        <div class="row g-4">

            @foreach($requests as $enrollmentRequest)

                <div class="col-lg-6">

                    <div class="student-card p-4 h-100">

                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">

                            <div>
                                <div class="text-muted small mb-1">
                                    Zahtev #{{ $enrollmentRequest->id }}
                                </div>

                                <h5 class="fw-bold mb-1">
                                    {{ $enrollmentRequest->offer?->name ?? 'Ponuda nije dostupna' }}
                                </h5>

                                <div class="text-muted small">
                                    Poslato:
                                    {{ $enrollmentRequest->created_at->format('d.m.Y. H:i') }}
                                </div>
                            </div>

                            @if($enrollmentRequest->isPending())
                                <span class="badge bg-warning-subtle text-warning-emphasis">
                                    <i class="bi bi-clock me-1"></i>
                                    Čeka potvrdu
                                </span>
                            @elseif($enrollmentRequest->isConfirmed())
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Potvrđeno
                                </span>
                            @elseif($enrollmentRequest->isRejected())
                                <span class="badge bg-danger-subtle text-danger-emphasis">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Odbijeno
                                </span>
                            @endif

                        </div>

                        @if($enrollmentRequest->offer)
                            <div class="mb-3">
                                <div class="fw-semibold small mb-2">
                                    Kursevi u ponudi
                                </div>

                                @foreach($enrollmentRequest->offer->courses as $course)
                                    <div class="small mb-1">
                                        <i class="bi bi-check2 text-success me-2"></i>
                                        {{ $course->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Redovna cena</span>
                            <span>
                                {{ number_format($enrollmentRequest->regular_amount, 0, ',', '.') }}
                                RSD
                            </span>
                        </div>

                        @if($enrollmentRequest->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>
                                    Referral popust
                                    @if($enrollmentRequest->referralCode)
                                        ({{ $enrollmentRequest->referralCode->code }})
                                    @endif
                                </span>

                                <span>
                                    -{{ number_format($enrollmentRequest->discount_amount, 0, ',', '.') }}
                                    RSD
                                </span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-semibold">
                                Ukupno za uplatu
                            </span>

                            <span class="fs-4 fw-bold text-primary">
                                {{ number_format($enrollmentRequest->amount, 0, ',', '.') }}
                                RSD
                            </span>
                        </div>

                        @if($enrollmentRequest->isPending())

                            <div class="alert alert-info mt-4 mb-0">
                                <div class="fw-semibold mb-2">
                                    <i class="bi bi-bank me-2"></i>
                                    Bankovna uplata
                                </div>

                                <p class="small mb-2">
                                    Tvoj zahtev je uspešno evidentiran.
                                    Uplati prikazani iznos prema instrukcijama
                                    koje će biti dostupne ovde.
                                </p>

                                <div class="small">
                                    <strong>Identifikacija zahteva:</strong>
                                    #{{ $enrollmentRequest->id }}
                                </div>

                                <div class="small mt-2">
                                    Pristup kursevima biće aktiviran
                                    nakon što administrator potvrdi uplatu.
                                </div>
                            </div>

                        @elseif($enrollmentRequest->isConfirmed())

                            <div class="alert alert-success mt-4 mb-0">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Uplata je potvrđena. Pristup kursevima je aktiviran.

                                @if($enrollmentRequest->confirmed_at)
                                    <div class="small mt-2">
                                        Datum potvrde:
                                        {{ $enrollmentRequest->confirmed_at->format('d.m.Y. H:i') }}
                                    </div>
                                @endif
                            </div>

                        @elseif($enrollmentRequest->isRejected())

                            <div class="alert alert-danger mt-4 mb-0">
                                <div class="fw-semibold">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Zahtev je odbijen.
                                </div>

                                @if($enrollmentRequest->admin_note)
                                    <div class="small mt-2">
                                        {{ $enrollmentRequest->admin_note }}
                                    </div>
                                @endif
                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @endif

@endsection
