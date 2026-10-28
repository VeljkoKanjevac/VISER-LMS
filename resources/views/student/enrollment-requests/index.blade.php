
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


                            <div class="card border-primary-subtle bg-primary-subtle mt-4">
                                <div class="card-body p-3 p-md-4">

                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-bank me-2"></i>
                                        Instrukcije za uplatu
                                    </h6>

                                    @if(
                                        config('payment.bank_account') &&
                                        config('payment.recipient_name') &&
                                        config('payment.recipient_address')
                                    )

                                        <div class="mb-3">
                                            <div class="text-muted small">Primalac</div>
                                            <div class="fw-semibold">
                                                {{ config('payment.recipient_name') }}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="text-muted small">Adresa primaoca</div>
                                            <div class="fw-semibold">
                                                {{ config('payment.recipient_address') }}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="text-muted small">Broj računa</div>
                                            <div class="fw-bold fs-5 text-break">
                                                {{ config('payment.bank_account') }}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="text-muted small">Svrha uplate</div>
                                            <div class="fw-semibold">
                                                {{ config('payment.payment_purpose') }}
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <div class="text-muted small">
                                                Iznos za uplatu
                                            </div>

                                            <div class="fw-bold fs-3 text-primary">
                                                {{ number_format($enrollmentRequest->amount, 0, ',', '.') }}
                                                RSD
                                            </div>
                                        </div>

                                        <div class="small mb-3">
                                            <strong>Broj zahteva:</strong>
                                            #{{ $enrollmentRequest->id }}
                                        </div>

                                        <div class="alert alert-light border small">
                                            Nakon izvršene uplate pošalji potvrdu putem
                                            WhatsApp-a. Pristup kursevima biće aktiviran
                                            kada administrator proveri i potvrdi uplatu.
                                        </div>

                                        @if(config('payment.support_whatsapp'))

                                            @php
                                                $whatsappMessage = implode("\n", [
                                                    'Zdravo, izvršio/la sam uplatu za EDU Platform.',
                                                    '',
                                                    'Broj zahteva: #' . $enrollmentRequest->id,
                                                    'Ponuda: ' . ($enrollmentRequest->offer?->name ?? 'Nepoznata ponuda'),
                                                    'Iznos: ' . number_format($enrollmentRequest->amount, 0, ',', '.') . ' RSD',
                                                    '',
                                                    'U prilogu šaljem potvrdu o uplati.',
                                                ]);

                                                $whatsappUrl = 'https://wa.me/'
                                                    . config('payment.support_whatsapp')
                                                    . '?text='
                                                    . rawurlencode($whatsappMessage);
                                            @endphp

                                            <a
                                                href="{{ $whatsappUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-success w-100 fw-semibold py-2"
                                            >
                                                <i class="bi bi-whatsapp me-2"></i>
                                                Pošalji potvrdu putem WhatsApp-a
                                            </a>

                                            <div class="text-center mt-3">

                                                <p class="text-muted small mb-2">
                                                    Ako ne koristiš WhatsApp na ovom uređaju,
                                                    možeš ručno započeti razgovor na broj:
                                                </p>

                                                <div class="fw-bold fs-5 mb-2">
                                                    <i class="bi bi-telephone me-2"></i>
                                                    +{{ config('payment.support_whatsapp') }}
                                                </div>

                                                <p class="text-muted small mb-0">
                                                    Prilikom slanja potvrde obavezno navedi
                                                    broj zahteva
                                                    <strong>#{{ $enrollmentRequest->id }}</strong>
                                                    kako bismo mogli da identifikujemo tvoju uplatu.
                                                </p>

                                            </div>

                                        @endif

                                    @else

                                        <div class="alert alert-warning mb-0">
                                            Podaci za bankovnu uplatu trenutno nisu dostupni.
                                            Kontaktiraj podršku pre izvršenja uplate.
                                        </div>

                                    @endif

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
