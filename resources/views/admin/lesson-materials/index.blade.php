@extends('layouts.admin')

@section('title', 'Materijali lekcija')
@section('page-title', 'Materijali lekcija')

@section('content')

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            @if ($selectedLesson)
                <div class="text-muted small mb-1">
                    {{ $selectedLesson->section->course->faculty->short_name
                        ?? $selectedLesson->section->course->faculty->name }}
                    /
                    {{ $selectedLesson->section->course->name }}
                    /
                    {{ $selectedLesson->section->title }}
                </div>

                <h5 class="mb-1">
                    Materijali: {{ $selectedLesson->title }}
                </h5>

                <p class="text-muted mb-0">
                    Upravljanje PDF materijalima izabrane lekcije.
                </p>
            @else
                <h5 class="mb-1">Svi materijali</h5>
                <p class="text-muted mb-0">
                    Pregled materijala svih lekcija.
                </p>
            @endif
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($selectedLesson)
                <a
                    href="{{ route('admin.lesson-materials.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Svi materijali
                </a>
            @endif

            <a
                href="{{ route('admin.lessons.index') }}"
                class="btn btn-outline-primary"
            >
                Nazad na lekcije
            </a>
        </div>

    </div>

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

    <div class="row g-4">

        {{-- Forma za dodavanje --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dodaj materijal</h5>
                </div>

                <div class="card-body">
                    @include('admin.lesson-materials._form')
                </div>
            </div>
        </div>

        {{-- Lista materijala --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="mb-0">Postojeći materijali</h5>
                </div>

                <div class="card-body p-0">

                    @if ($materials->isEmpty())

                        <div class="text-center text-muted py-5">
                            Trenutno nema dodatih materijala.
                        </div>

                    @else

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                <tr>
                                    <th>Materijal</th>
                                    <th>Lekcija</th>
                                    <th>Redosled</th>
                                    <th class="text-end">Akcije</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($materials as $material)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $material->title }}
                                            </div>

                                            <small class="text-muted">
                                                PDF dokument
                                            </small>
                                        </td>

                                        <td>
                                            <div>
                                                {{ $material->lesson->title }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $material->lesson->section->course->name }}
                                                —
                                                {{ $material->lesson->section->title }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $material->sort_order }}
                                        </td>

                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">

                                                <a
                                                    href="{{ route('admin.lesson-materials.download', $material) }}"
                                                    class="btn btn-sm btn-outline-primary text-nowrap"
                                                    style="width: 100px;"
                                                >
                                                    Preuzmi
                                                </a>

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary text-nowrap"
                                                    style="width: 100px;"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#edit-material-{{ $material->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="edit-material-{{ $material->id }}"
                                                >
                                                    Izmeni
                                                </button>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.lesson-materials.destroy', $material) }}"
                                                    class="m-0"
                                                    onsubmit="return confirm('Da li sigurno želiš da obrišeš ovaj materijal?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger text-nowrap"
                                                        style="width: 100px;"
                                                    >
                                                        Obriši
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Forma za izmenu --}}
                                    <tr>
                                        <td colspan="4" class="p-0 border-0">

                                            <div
                                                class="collapse"
                                                id="edit-material-{{ $material->id }}"
                                            >
                                                <div class="bg-light p-3 border-bottom">

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.lesson-materials.update', $material) }}"
                                                    >
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row g-3">

                                                            <div class="col-md-5">
                                                                <label
                                                                    for="title-{{ $material->id }}"
                                                                    class="form-label"
                                                                >
                                                                    Naziv materijala
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    id="title-{{ $material->id }}"
                                                                    name="title"
                                                                    class="form-control"
                                                                    value="{{ $material->title }}"
                                                                    required
                                                                >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label
                                                                    for="lesson-{{ $material->id }}"
                                                                    class="form-label"
                                                                >
                                                                    Lekcija
                                                                </label>

                                                                <select
                                                                    id="lesson-{{ $material->id }}"
                                                                    name="lesson_id"
                                                                    class="form-select"
                                                                    required
                                                                >
                                                                    @foreach ($lessons as $lesson)
                                                                        <option
                                                                            value="{{ $lesson->id }}"
                                                                            @selected($material->lesson_id == $lesson->id)
                                                                        >
                                                                            {{ $lesson->title }}
                                                                            —
                                                                            {{ $lesson->section->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label
                                                                    for="sort-{{ $material->id }}"
                                                                    class="form-label"
                                                                >
                                                                    Redosled
                                                                </label>

                                                                <input
                                                                    type="number"
                                                                    id="sort-{{ $material->id }}"
                                                                    name="sort_order"
                                                                    min="0"
                                                                    class="form-control"
                                                                    value="{{ $material->sort_order }}"
                                                                    required
                                                                >
                                                            </div>

                                                        </div>

                                                        <div class="mt-3 d-flex gap-2">
                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-primary"
                                                            >
                                                                Sačuvaj izmene
                                                            </button>

                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-outline-secondary"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#edit-material-{{ $material->id }}"
                                                            >
                                                                Odustani
                                                            </button>
                                                        </div>

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
        </div>

    </div>

@endsection
