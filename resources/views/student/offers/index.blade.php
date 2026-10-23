
@extends('layouts.student')

@section('title', 'Dostupne ponude')

@section('page-title', 'Dostupne ponude')

@section('content')

    <p class="text-muted mb-4">
        Izaberi ponudu koja ti odgovara i pošalji zahtev za upis.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($offers->isEmpty())

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Trenutno nema dostupnih ponuda.
        </div>

    @else

        <div class="row g-4">

            @foreach($offers as $offer)

                <div class="col-md-6 col-xl-4">

                    <div class="student-card h-100 p-4 d-flex flex-column">

                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary">
                                <i class="bi bi-mortarboard me-1"></i>
                                {{ $offer->faculty->name }}
                            </span>
                        </div>

                        <h4 class="fw-bold mb-2">
                            {{ $offer->name }}
                        </h4>

                        @if($offer->description)
                            <p class="text-muted small mb-3">
                                {{ $offer->description }}
                            </p>
                        @endif

                        <div class="mb-4">

                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-journal-bookmark me-2"></i>
                                Uključeni kursevi
                            </h6>

                            @foreach($offer->courses as $course)
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>

                                    <span>
                                        {{ $course->name }}
                                    </span>
                                </div>
                            @endforeach

                        </div>

                        <div class="mt-auto">

                            <hr class="my-4">

                            <div class="mb-3">

                                <div class="text-muted small mb-1">
                                    Cena ponude
                                </div>

                                <div class="fs-2 fw-bold">
                                    {{ number_format($offer->price, 0, ',', '.') }}
                                    <span class="fs-6 text-muted fw-normal">
                                        RSD
                                    </span>
                                </div>

                                @if($offer->referral_discount > 0)

                                    <div class="text-success small mt-2">
                                        <i class="bi bi-tag-fill me-1"></i>

                                        Uz referral kod uštedi
                                        <strong>
                                            {{ number_format($offer->referral_discount, 0, ',', '.') }}
                                            RSD
                                        </strong>
                                    </div>

                                    <div class="text-muted small mt-1">
                                        Cena sa referral kodom:
                                        <strong>
                                            {{ number_format(
                                                $offer->price - $offer->referral_discount,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                            RSD
                                        </strong>
                                    </div>

                                @endif

                            </div>

                            <form
                                method="POST"
                                action="{{ route('student.enrollment-requests.store', $offer) }}"
                            >
                                @csrf

                                <div class="mb-3">

                                    <label
                                        for="referral_code_{{ $offer->id }}"
                                        class="form-label fw-semibold small"
                                    >
                                        Referral kod (opciono)
                                    </label>

                                    <input
                                        type="text"
                                        name="referral_code"
                                        id="referral_code_{{ $offer->id }}"
                                        class="form-control"
                                        placeholder="Unesi referral kod"
                                        maxlength="50"
                                    >

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100 py-2 fw-semibold"
                                >
                                    <i class="bi bi-cart-check me-2"></i>
                                    Pošalji zahtev za upis
                                </button>

                            </form>

                            <div class="mt-3 text-muted small">
                                <i class="bi bi-shield-check me-1"></i>
                                Pristup kursevima dobijaš nakon
                                potvrde bankovne uplate.
                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

@endsection
