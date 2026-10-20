@extends('layouts.admin')

@section('title', 'Lekcije')
@section('page-title', 'Lekcije')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a
            href="{{ route('admin.lessons.create') }}"
            class="btn btn-primary"
        >
            Dodaj lekciju
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($lessons->isEmpty())

                <div class="text-center text-muted py-5">
                    Trenutno nema lekcija.
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>Naziv</th>
                            <th>Kurs</th>
                            <th>Tematska oblast</th>
                            <th>Trajanje</th>
                            <th>Pristup</th>
                            <th>Status</th>
                            <th>Materijali</th>
                            <th class="text-end">Akcije</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($lessons as $lesson)

                            @php
                                $isScheduled = $lesson->is_published
                                    && $lesson->published_at
                                    && $lesson->published_at->isFuture();
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $lesson->title }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $lesson->slug }}
                                    </small>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        {{ $lesson->section->course->faculty->short_name
                                            ?? $lesson->section->course->faculty->name }}
                                    </small>

                                    <div>
                                        {{ $lesson->section->course->name }}
                                    </div>
                                </td>

                                <td>
                                    {{ $lesson->section->title }}
                                </td>

                                <td>
                                    @if ($lesson->duration_minutes)
                                        {{ $lesson->duration_minutes }} min
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if ($lesson->is_free)
                                        <span class="badge text-bg-info">
                                                Besplatna
                                            </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                                Premium
                                            </span>
                                    @endif
                                </td>

                                <td>
                                    @if (! $lesson->is_published)

                                        <span class="badge text-bg-secondary">
                                                Sakrivena
                                            </span>

                                    @elseif ($isScheduled)

                                        <span class="badge text-bg-warning">
                                                Zakazana
                                            </span>

                                        <div class="small text-muted mt-1">
                                            {{ $lesson->published_at->format('d.m.Y. H:i') }}
                                        </div>

                                    @else

                                        <span class="badge text-bg-success">
                                                Objavljena
                                            </span>

                                    @endif
                                </td>

                                <td>
                                    {{ $lesson->materials_count }}
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">

                                        <a
                                            href="{{ route('admin.lessons.edit', $lesson) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            style="width: 100px;"
                                        >
                                            Izmeni
                                        </a>

                                        @if ($lesson->materials_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.lessons.destroy', $lesson) }}"
                                                onsubmit="return confirm('Da li sigurno želiš da obrišeš ovu lekciju?');"
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

                                            <form
                                                method="POST"
                                                action="{{ route('admin.lessons.toggle-published', $lesson) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm {{ $lesson->is_published ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    style="width: 100px;"
                                                    @if ($lesson->is_published)
                                                        onclick="return confirm('Da li želiš da sakriješ ovu lekciju?');"
                                                    @endif
                                                >
                                                    {{ $lesson->is_published ? 'Sakrij' : 'Objavi' }}
                                                </button>
                                            </form>

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
