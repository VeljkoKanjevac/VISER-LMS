<div class="mb-3">
    <label for="course_id" class="form-label">
        Kurs
    </label>

    <select
        id="course_id"
        name="course_id"
        class="form-select @error('course_id') is-invalid @enderror"
        required
    >
        <option value="">Izaberi kurs</option>

        @foreach ($courses as $course)
            <option
                value="{{ $course->id }}"
                @selected(old('course_id', $section?->course_id) == $course->id)
            >
                {{ $course->faculty->short_name ?? $course->faculty->name }}
                — {{ $course->name }}

                @if (! $course->is_active)
                    (neaktivan)
                @endif
            </option>
        @endforeach
    </select>

    @error('course_id')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="title" class="form-label">
        Naziv tematske oblasti
    </label>

    <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $section?->title) }}"
        required
    >

    @error('title')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
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
    >{{ old('description', $section?->description) }}</textarea>

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
        value="{{ old('sort_order', $section?->sort_order ?? 0) }}"
        required
    >

    @error('sort_order')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror

    <div class="form-text">
        Određuje redosled tematskih oblasti unutar kursa.
    </div>
</div>

<div class="mb-4">
    <input
        type="hidden"
        name="is_published"
        value="0"
    >

    <div class="form-check">
        <input
            type="checkbox"
            id="is_published"
            name="is_published"
            value="1"
            class="form-check-input @error('is_published') is-invalid @enderror"
            @checked(old('is_published', $section?->is_published ?? false))
        >

        <label
            for="is_published"
            class="form-check-label"
        >
            Objavljeno studentima
        </label>

        @error('is_published')
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
        href="{{ route('admin.sections.index') }}"
        class="btn btn-outline-secondary"
    >
        Odustani
    </a>
</div>
