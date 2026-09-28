@extends('layouts.admin')

@section('title', 'Fakulteti')

@section('page-title', 'Fakulteti')

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
            href="{{ route('admin.faculties.create') }}"
            class="btn btn-primary"
        >
            Dodaj fakultet
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            @if ($faculties->isEmpty())

                <div class="text-center text-muted py-5">
                    Trenutno nema fakulteta.
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>Naziv</th>
                            <th>Skraćeni naziv</th>
                            <th>Slug</th>
                            <th>Redosled</th>
                            <th>Status</th>
                            <th class="text-end">
                                Akcije
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($faculties as $faculty)

                            <tr>
                                <td>
                                    {{ $faculty->name }}
                                </td>

                                <td>
                                    {{ $faculty->short_name ?? '—' }}
                                </td>

                                <td>
                                    <code>
                                        {{ $faculty->slug }}
                                    </code>
                                </td>

                                <td>
                                    {{ $faculty->sort_order }}
                                </td>

                                <td>
                                    @if ($faculty->is_active)
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
                                            href="{{ route('admin.faculties.edit', $faculty) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Izmeni
                                        </a>

                                        @if ($faculty->courses_count === 0 && $faculty->offers_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.faculties.destroy', $faculty) }}"
                                                onsubmit="return confirm('Da li sigurno želiš da obrišeš ovaj fakultet?');"
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
                                                action="{{ route('admin.faculties.toggle-active', $faculty) }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                @if ($faculty->is_active)
                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                        onclick="return confirm('Da li sigurno želiš da deaktiviraš ovaj fakultet?');"
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
