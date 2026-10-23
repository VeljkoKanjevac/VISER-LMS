
@extends('layouts.admin')

@section('title', 'Referral kodovi')
@section('page-title', 'Referral kodovi')

@section('content')

    {{-- Poruke --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Zatvori"
            ></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Zatvori"
            ></button>
        </div>
    @endif

    {{-- Naslov i dugme za kreiranje --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Upravljanje referral kodovima</h4>

            <p class="text-muted mb-0">
                Svaki student može imati jedan referral kod koji važi za sve ponude.
            </p>
        </div>

        <a
            href="{{ route('admin.referral-codes.create') }}"
            class="btn btn-primary"
        >
            + Novi referral kod
        </a>
    </div>

    {{-- Statistika --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        Ukupno referral kodova
                    </div>

                    <h3 class="mb-0">
                        {{ $referralCodes->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        Aktivni kodovi
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ $referralCodes->where('is_active', true)->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        Neaktivni kodovi
                    </div>

                    <h3 class="mb-0 text-secondary">
                        {{ $referralCodes->where('is_active', false)->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista referral kodova --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista referral kodova</h5>

            <span class="text-muted small">
                Ukupno: {{ $referralCodes->count() }}
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Korisnik</th>
                        <th>Referral kod</th>
                        <th>Status</th>
                        <th>Provizije</th>
                        <th>Datum kreiranja</th>
                        <th class="text-end">Akcije</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($referralCodes as $referralCode)
                        <tr>
                            {{-- Korisnik --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $referralCode->user?->name ?? 'Nepoznat korisnik' }}
                                </div>

                                <div class="text-muted small">
                                    {{ $referralCode->user?->email ?? '—' }}
                                </div>
                            </td>

                            {{-- Kod --}}
                            <td>
                                <code class="fw-semibold">
                                    {{ $referralCode->code }}
                                </code>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if ($referralCode->is_active)
                                    <span class="badge bg-success">
                                            Aktivan
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            Neaktivan
                                        </span>
                                @endif
                            </td>

                            {{-- Provizije --}}
                            <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $referralCode->commissions_count }}
                                    </span>
                            </td>

                            {{-- Datum --}}
                            <td class="text-nowrap">
                                {{ $referralCode->created_at?->format('d.m.Y.') ?? '—' }}
                            </td>

                            {{-- Akcije --}}
                            <td class="text-end">
                                <div class="d-flex flex-wrap justify-content-end gap-2">

                                    {{-- Aktivacija / deaktivacija --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.referral-codes.toggle-active', $referralCode) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm {{ $referralCode->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        >
                                            {{ $referralCode->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}
                                        </button>
                                    </form>

                                    {{-- Brisanje --}}
                                    @if ($referralCode->commissions_count === 0)
                                        <form
                                            method="POST"
                                            action="{{ route('admin.referral-codes.destroy', $referralCode) }}"
                                            onsubmit="return confirm('Da li sigurno želiš da obrišeš ovaj referral kod?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Obriši
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled
                                            title="Kod sa evidentiranim provizijama ne može se obrisati."
                                        >
                                            Obriši
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-5"
                            >
                                Još nema dodeljenih referral kodova.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
