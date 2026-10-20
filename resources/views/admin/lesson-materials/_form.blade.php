<form
    method="POST"
    action="{{ route('admin.lesson-materials.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    <div class="mb-3">
        <label for="lesson_id" class="form-label">
            Lekcija
        </label>

        <select
            id="lesson_id"
            name="lesson_id"
            class="form-select @error('lesson_id') is-invalid @enderror"
            required
        >
            <option value="">Izaberi lekciju</option>

            @foreach ($lessons as $lesson)
                <option
                    value="{{ $lesson->id }}"
                    @selected(old('lesson_id', $selectedLessonId ?? null) == $lesson->id)
                >
                    {{ $lesson->section->course->faculty->short_name
                        ?? $lesson->section->course->faculty->name }}
                    — {{ $lesson->section->course->name }}
                    — {{ $lesson->section->title }}
                    — {{ $lesson->title }}
                </option>
            @endforeach
        </select>

        @error('lesson_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">
            Naziv materijala
        </label>

        <input
            type="text"
            id="title"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title') }}"
            placeholder="Zadaci za vežbanje"
            required
        >

        @error('title')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">
            PDF fajl
        </label>

        <input
            type="file"
            id="file"
            name="file"
            accept=".pdf,application/pdf"
            class="form-control @error('file') is-invalid @enderror"
            required
        >

        <div class="form-text">
            Dozvoljen je PDF format, maksimalno 20 MB.
        </div>

        @error('file')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="sort_order" class="form-label">
            Redosled prikaza
        </label>

        <input
            type="number"
            id="sort_order"
            name="sort_order"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', 0) }}"
            required
        >

        @error('sort_order')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        Dodaj materijal
    </button>
</form>
