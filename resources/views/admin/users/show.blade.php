
@extends('layouts.admin')

@section('title', 'Detalji korisnika')
@section('page-title', 'Detalji korisnika')

@section('content')

    {{-- Povratak --}}
    <div class="mb-4">
        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-outline-secondary"
        >
            &larr; Nazad na korisnike
        </a>
    </div>

    {{-- Osnovni podaci --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Osnovni podaci</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="text-muted small">Ime i prezime</div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Email adresa</div>
                    <div>{{ $user->email }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Uloga</div>

                    @if ($user->role === 'admin')
                        <span class="badge bg-primary">Administrator</span>
                    @else
                        <span class="badge bg-secondary">Student</span>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Email verifikacija</div>

                    @if ($user->email_verified_at)
                        <span class="badge bg-success">Verifikovan</span>
                    @else
                        <span class="badge bg-warning text-dark">
                            Nije verifikovan
                        </span>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Datum registracije</div>
                    <div>{{ $user->created_at->format('d.m.Y. H:i') }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- Kursevi --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                Upisani kursevi
                <span class="badge bg-secondary ms-2">
                    {{ $user->enrollments->count() }}
                </span>
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Kurs</th>
                        <th>Datum upisa</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($user->enrollments as $enrollment)
                        <tr>
                            <td>
                                {{ $enrollment->course?->name ?? 'Nepoznat kurs' }}
                            </td>

                            <td>
                                {{ $enrollment->enrolled_at
                                    ? \Illuminate\Support\Carbon::parse($enrollment->enrolled_at)->format('d.m.Y.')
                                    : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">
                                Korisnik još nema upisanih kurseva.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Uplate --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                Uplate
                <span class="badge bg-secondary ms-2">
                    {{ $user->payments->count() }}
                </span>
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Iznos</th>
                        <th>Status</th>
                        <th>Datum</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($user->payments as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>

                            <td>
                                {{ number_format($payment->amount, 0, ',', '.') }}
                                RSD
                            </td>

                            <td>
                                @if ($payment->status === 'paid')
                                    <span class="badge bg-success">
                                            Plaćeno
                                        </span>
                                @elseif ($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                            Na čekanju
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            {{ $payment->status }}
                                        </span>
                                @endif
                            </td>

                            <td>
                                {{ $payment->created_at?->format('d.m.Y.') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Korisnik nema evidentiranih uplata.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Referral kod --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                Referral kod
                <span class="badge bg-secondary ms-2">
                    {{ $user->referralCode ? 1 : 0 }}
                </span>
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Kod</th>
                        <th>Status</th>
                        <th>Datum kreiranja</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if ($user->referralCode)
                        <tr>
                            <td>
                                <code>{{ $user->referralCode->code }}</code>
                            </td>

                            <td>
                                @if ($user->referralCode->is_active)
                                    <span class="badge bg-success">
                                            Aktivan
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            Neaktivan
                                        </span>
                                @endif
                            </td>

                            <td>
                                {{ $user->referralCode->created_at?->format('d.m.Y.') ?? '—' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Korisnik nema referral kod.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
