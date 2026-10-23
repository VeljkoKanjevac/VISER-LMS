@extends('layouts.admin')

@section('title', 'Ponude')
@section('page-title', 'Ponude')

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

    {{-- Zaglavlje --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Sve ponude</h5>

        <a
            href="{{ route('admin.offers.create') }}"
            class="btn btn-primary"
        >
            Dodaj ponudu
        </a>
    </div>

    {{-- Tabela --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($offers->isEmpty())
                <div class="text-center text-muted py-5">
                    <h6>Trenutno nema ponuda.</h6>
                    <p class="mb-0">
                        Klikni na „Dodaj ponudu“ da kreiraš prvu.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Ponuda</th>
                            <th>Kursevi</th>
                            <th>Cena</th>
                            <th>Referral</th>
                            <th>Status</th>
                            <th class="text-end">Akcije</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                {{-- Naziv i fakultet --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $offer->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $offer->faculty->short_name
                                            ?? $offer->faculty->name }}
                                    </small>

                                    <div class="small text-muted">
                                        {{ $offer->slug }}
                                    </div>
                                </td>

                                {{-- Kursevi --}}
                                <td>
                                    @forelse ($offer->courses as $course)
                                        <div class="small mb-1">
                                            {{ $course->name }}
                                        </div>
                                    @empty
                                        <span class="text-muted small">
                                                Nema kurseva
                                            </span>
                                    @endforelse
                                </td>

                                {{-- Cena --}}
                                <td class="text-nowrap">
                                    <strong>
                                        {{ number_format($offer->price, 0, ',', '.') }}
                                        RSD
                                    </strong>
                                </td>

                                {{-- Referral --}}
                                <td class="text-nowrap">
                                    <div class="small">
                                        Popust:
                                        <strong>
                                            {{ number_format($offer->referral_discount, 0, ',', '.') }}
                                        </strong>
                                        RSD
                                    </div>

                                    <div class="small text-muted">
                                        Provizija:
                                        {{ number_format($offer->referral_commission, 0, ',', '.') }}
                                        RSD
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if ($offer->is_active)
                                        <span class="badge bg-success">
                                                Aktivna
                                            </span>
                                    @else
                                        <span class="badge bg-secondary">
                                                Neaktivna
                                            </span>
                                    @endif
                                </td>

                                {{-- Akcije --}}
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">

                                        {{-- Izmena --}}
                                        <a
                                            href="{{ route('admin.offers.edit', $offer) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            style="width: 100px;"
                                        >
                                            Izmeni
                                        </a>

                                        {{-- Aktivacija / deaktivacija --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.offers.toggle-active', $offer) }}"
                                            class="m-0"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $offer->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                style="width: 110px;"
                                            >
                                                {{ $offer->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}
                                            </button>
                                        </form>

                                        {{-- Brisanje --}}
                                        @if (
                                            $offer->payments_count === 0
                                        )
                                            <form
                                                method="POST"
                                                action="{{ route('admin.offers.destroy', $offer) }}"
                                                class="m-0"
                                                onsubmit="return confirm('Da li sigurno želiš da obrišeš ponudu?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    style="width: 100px;"
                                                >
                                                    Obriši
                                                </button>
                                            </form>
                                        @else
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                style="width: 100px;"
                                                disabled
                                                title="Ponuda ima povezane uplate ili referral kodove"
                                            >
                                                Obriši
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

@endsection
