@php
    $duration = $consultation
        ? $consultation->starts_at->diffInMinutes($consultation->ends_at)
        : 60;
@endphp

<div class="mb-3">
    <label for="course_id" class="form-label">Kurs</label>

    <select
        name="course_id"
        id="course_id"
        class="form-select @error('course_id') is-invalid @enderror"
        required
    >
        <option value="">Izaberi kurs</option>

        @foreach ($courses as $course)
            <option
                value="{{ $course->id }}"
                @selected(old('course_id', $consultation?->course_id) == $course->id)
            >
                {{ $course->faculty->short_name ?? $course->faculty->name }}
                — {{ $course->name }}
            </option>
        @endforeach
    </select>

    @error('course_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="title" class="form-label">Naziv konsultacije</label>

    <input
        type="text"
        name="title"
        id="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $consultation?->title) }}"
        required
    >

    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="starts_at" class="form-label">Datum i vreme početka</label>

    <input
        type="datetime-local"
        name="starts_at"
        id="starts_at"
        class="form-control @error('starts_at') is-invalid @enderror"
        value="{{ old('starts_at', $consultation?->starts_at?->format('Y-m-d\TH:i')) }}"
        required
    >

    @error('starts_at')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="duration_minutes" class="form-label">
        Trajanje (minuti)
    </label>

    <input
        type="number"
        name="duration_minutes"
        id="duration_minutes"
        min="1"
        max="480"
        class="form-control @error('duration_minutes') is-invalid @enderror"
        value="{{ old('duration_minutes', $duration) }}"
        required
    >

    @error('duration_minutes')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="form-label">Opis</label>

    <textarea
        name="description"
        id="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
    >{{ old('description', $consultation?->description) }}</textarea>

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary" style="min-width: 110px;">
        Sačuvaj
    </button>

    <a
        href="{{ route('admin.consultations.index') }}"
        class="btn btn-outline-secondary"
        style="min-width: 110px;"
    >
        Odustani
    </a>
</div>
