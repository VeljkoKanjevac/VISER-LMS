@extends('layouts.admin')

@section('title', 'Tematske oblasti')

@section('page-title', 'Tematske oblasti')

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
            href="{{ route('admin.sections.create') }}"
            class="btn btn-primary"
        >
            Dodaj tematsku oblast
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($sections->isEmpty())

                <div class="text-center text-muted py-5">
                    Trenutno nema tematskih oblasti.
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>Naziv</th>
                            <th>Kurs</th>
                            <th>Fakultet</th>
                            <th>Redosled</th>
                            <th>Lekcije</th>
                            <th>Status</th>
                            <th class="text-end">
                                Akcije
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($sections as $section)

                            <tr>
                                <td>
                                    {{ $section->title }}
                                </td>

                                <td>
                                    {{ $section->course->name }}
                                </td>

                                <td>
                                    {{ $section->course->faculty->short_name
                                        ?? $section->course->faculty->name }}
                                </td>

                                <td>
                                    {{ $section->sort_order }}
                                </td>

                                <td>
                                    {{ $section->lessons_count }}
                                </td>

                                <td>
                                    @if ($section->is_published)

                                        <span class="badge text-bg-success">
                                                Objavljeno
                                            </span>

                                    @else

                                        <span class="badge text-bg-secondary">
                                                Sakriveno
                                            </span>

                                    @endif
                                </td>

                                <td class="text-end">

                                    <div class="d-inline-flex gap-2">

                                        <a
                                            href="{{ route('admin.sections.edit', $section) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Izmeni
                                        </a>

                                        @if ($section->lessons_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.sections.destroy', $section) }}"
                                                onsubmit="return confirm('Da li sigurno želiš da obrišeš ovu tematsku oblast?');"
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
                                                action="{{ route('admin.sections.toggle-published', $section) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                @if ($section->is_published)

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                        style="width: 100px;"
                                                        onclick="return confirm('Da li sigurno želiš da sakriješ ovu tematsku oblast?');"
                                                    >
                                                        Sakrij
                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        style="width: 100px;"
                                                    >
                                                        Objavi
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
