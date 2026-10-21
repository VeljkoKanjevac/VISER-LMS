@extends('layouts.admin')

@section('title', 'Konsultacije')
@section('page-title', 'Konsultacije')

@section('content')

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Zakazani i prethodni termini</h5>

        <a
            href="{{ route('admin.consultations.create') }}"
            class="btn btn-primary"
        >
            Dodaj konsultaciju
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($consultations->isEmpty())
                <div class="text-center text-muted py-5">
                    Trenutno nema konsultacija.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Konsultacija</th>
                            <th>Termin</th>
                            <th>Status</th>
                            <th>Link</th>
                            <th class="text-end">Akcije</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($consultations as $consultation)
                            @php
                                $now = now();

                                $status = ! $consultation->is_active
                                    ? 'Neaktivna'
                                    : ($consultation->ends_at->lte($now)
                                        ? 'Završena'
                                        : ($consultation->starts_at->lte($now)
                                            ? 'U toku'
                                            : 'Zakazana'));

                                $statusClass = match ($status) {
                                    'Zakazana' => 'bg-primary',
                                    'U toku' => 'bg-success',
                                    'Završena' => 'bg-secondary',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $consultation->title }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $consultation->course->faculty->short_name
                                            ?? $consultation->course->faculty->name }}
                                        —
                                        {{ $consultation->course->name }}
                                    </small>

                                    @if ($consultation->description)
                                        <div class="small text-muted mt-1">
                                            {{ $consultation->description }}
                                        </div>
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    <div>
                                        {{ $consultation->starts_at->format('d.m.Y.') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $consultation->starts_at->format('H:i') }}
                                        –
                                        {{ $consultation->ends_at->format('H:i') }}
                                    </small>

                                    <div class="small text-muted">
                                        {{ (int) $consultation->starts_at->diffInMinutes($consultation->ends_at) }}
                                        min
                                    </div>
                                </td>

                                <td>
                                        <span class="badge {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                </td>

                                <td>
                                    @if ($consultation->meeting_url)
                                        <span class="badge bg-success">
                                                Link dodat
                                            </span>

                                        <div class="mt-1">
                                            <a
                                                href="{{ $consultation->meeting_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="small"
                                            >
                                                Otvori link
                                            </a>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">
                                                Bez linka
                                            </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">

                                        <a
                                            href="{{ route('admin.consultations.edit', $consultation) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            style="width: 100px;"
                                        >
                                            Izmeni
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-info text-nowrap"
                                            style="width: 110px;"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#link-{{ $consultation->id }}"
                                            aria-expanded="false"
                                            aria-controls="link-{{ $consultation->id }}"
                                        >
                                            {{ $consultation->meeting_url ? 'Izmeni link' : 'Dodaj link' }}
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.consultations.toggle-active', $consultation) }}"
                                            class="m-0"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $consultation->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                style="width: 100px;"
                                            >
                                                {{ $consultation->is_active ? 'Deaktiviraj' : 'Aktiviraj' }}
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.consultations.destroy', $consultation) }}"
                                            class="m-0"
                                            onsubmit="return confirm('Da li sigurno želiš da obrišeš konsultaciju?');"
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

                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5" class="p-0 border-0">
                                    <div
                                        class="collapse"
                                        id="link-{{ $consultation->id }}"
                                    >
                                        <div class="bg-light border-bottom p-3">
                                            <form
                                                method="POST"
                                                action="{{ route('admin.consultations.update-link', $consultation) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <label
                                                    for="meeting-url-{{ $consultation->id }}"
                                                    class="form-label"
                                                >
                                                    Link za sastanak
                                                </label>

                                                <input
                                                    type="url"
                                                    id="meeting-url-{{ $consultation->id }}"
                                                    name="meeting_url"
                                                    class="form-control mb-2"
                                                    value="{{ $consultation->meeting_url }}"
                                                    placeholder="https://meet.google.com/..."
                                                >

                                                <div class="form-text mb-3">
                                                    Ostavi prazno ako želiš da ukloniš postojeći link.
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-primary"
                                                    style="min-width: 100px;"
                                                >
                                                    Sačuvaj link
                                                </button>
                                            </form>
                                        </div>
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
