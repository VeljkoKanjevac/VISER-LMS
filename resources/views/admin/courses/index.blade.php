@extends('layouts.admin')

@section('title', 'Kursevi')

@section('page-title', 'Kursevi')

@section('content')

    @if (session('success'))
        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >
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
        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Zatvori"
            ></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a
            href="{{ route('admin.courses.create') }}"
            class="btn btn-primary"
        >
            Dodaj kurs
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($courses->isEmpty())

                <div class="text-center text-muted py-5">
                    Trenutno nema kurseva.
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>Naziv</th>
                            <th>Fakultet</th>
                            <th>Godina</th>
                            <th>Slug</th>
                            <th>Redosled</th>
                            <th>Status</th>
                            <th class="text-end">
                                Akcije
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($courses as $course)

                            <tr>
                                <td>
                                    {{ $course->name }}
                                </td>

                                <td>
                                    {{ $course->faculty->short_name ?? $course->faculty->name }}
                                </td>

                                <td>
                                    {{ $course->study_year }}.
                                </td>

                                <td>
                                    <code>
                                        {{ $course->slug }}
                                    </code>
                                </td>

                                <td>
                                    {{ $course->sort_order }}
                                </td>

                                <td>
                                    @if ($course->is_active)
                                        <span class="badge text-bg-success">
                                                Aktivan
                                            </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                                Neaktivan
                                            </span>
                                    @endif
                                </td>

                                <td class="text-end">

                                    <div class="d-inline-flex gap-2">

                                        <a
                                            href="{{ route('admin.courses.edit', $course) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Izmeni
                                        </a>

                                        @php
                                            $hasRelatedData =
                                                $course->sections_count > 0
                                                || $course->enrollments_count > 0
                                                || $course->consultations_count > 0
                                                || $course->offers_count > 0;
                                        @endphp

                                        @if (! $hasRelatedData)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.courses.destroy', $course) }}"
                                                onsubmit="return confirm('Da li sigurno želiš da obrišeš ovaj kurs?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    style="width: 83px;"
                                                >
                                                    Obriši
                                                </button>
                                            </form>

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('admin.courses.toggle-active', $course) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                @if ($course->is_active)

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                        style="width: 83px;"
                                                        onclick="return confirm('Da li sigurno želiš da deaktiviraš ovaj kurs?');"
                                                    >
                                                        Deaktiviraj
                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        style="width: 83px;"
                                                    >
                                                        Aktiviraj
                                                    </button>

                                                @endif

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
