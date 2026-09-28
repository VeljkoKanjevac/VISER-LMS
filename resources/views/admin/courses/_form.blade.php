<div class="mb-3">
    <label for="faculty_id" class="form-label">
        Fakultet
    </label>

    <select
        id="faculty_id"
        name="faculty_id"
        class="form-select @error('faculty_id') is-invalid @enderror"
        required
    >
        <option value="">Izaberi fakultet</option>

        @foreach ($faculties as $faculty)
            <option
                value="{{ $faculty->id }}"
                @selected(old('faculty_id', $course?->faculty_id) == $faculty->id)
            >
                {{ $faculty->short_name ?? $faculty->name }}

                @if (! $faculty->is_active)
                    (neaktivan)
                @endif
            </option>
        @endforeach
    </select>

    @error('faculty_id')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="study_year" class="form-label">
        Godina studija
    </label>

    <input
        type="number"
        id="study_year"
        name="study_year"
        min="1"
        max="10"
        class="form-control @error('study_year') is-invalid @enderror"
        value="{{ old('study_year', $course?->study_year) }}"
        required
    >

    @error('study_year')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">
        Naziv kursa
    </label>

    <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $course?->name) }}"
        required
    >

    @error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="slug" class="form-label">
        Slug
    </label>

    <input
        type="text"
        id="slug"
        name="slug"
        class="form-control @error('slug') is-invalid @enderror"
        value="{{ old('slug', $course?->slug) }}"
        required
    >

    @error('slug')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror

    <div class="form-text">
        Primer: inzenjerska-matematika
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">
        Opis
    </label>

    <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
    >{{ old('description', $course?->description) }}</textarea>

    @error('description')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="sort_order" class="form-label">
        Redosled prikaza
    </label>

    <input
        type="number"
        id="sort_order"
        name="sort_order"
        min="0"
        class="form-control @error('sort_order') is-invalid @enderror"
        value="{{ old('sort_order', $course?->sort_order ?? 0) }}"
        required
    >

    @error('sort_order')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-4">
    <input
        type="hidden"
        name="is_active"
        value="0"
    >

    <div class="form-check">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            class="form-check-input @error('is_active') is-invalid @enderror"
            @checked(old('is_active', $course?->is_active ?? true))
        >

        <label
            for="is_active"
            class="form-check-label"
        >
            Kurs je aktivan
        </label>

        @error('is_active')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button
        type="submit"
        class="btn btn-primary"
    >
        Sačuvaj
    </button>

    <a
        href="{{ route('admin.courses.index') }}"
        class="btn btn-outline-secondary"
    >
        Odustani
    </a>
</div>
