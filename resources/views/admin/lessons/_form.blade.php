<div class="mb-3">
    <label for="section_id" class="form-label">
        Tematska oblast
    </label>

    <select
        id="section_id"
        name="section_id"
        class="form-select @error('section_id') is-invalid @enderror"
        required
    >
        <option value="">Izaberi tematsku oblast</option>

        @foreach ($sections as $section)
            <option
                value="{{ $section->id }}"
                @selected(old('section_id', $lesson?->section_id) == $section->id)
            >
                {{ $section->course->faculty->short_name
                    ?? $section->course->faculty->name }}
                — {{ $section->course->name }}
                — {{ $section->title }}

                @if (! $section->is_published)
                    (sakrivena oblast)
                @elseif (! $section->course->is_active)
                    (neaktivan kurs)
                @endif
            </option>
        @endforeach
    </select>

    @error('section_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="title" class="form-label">Naziv lekcije</label>

    <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $lesson?->title) }}"
        required
    >

    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>

    <input
        type="text"
        id="slug"
        name="slug"
        class="form-control @error('slug') is-invalid @enderror"
        value="{{ old('slug', $lesson?->slug) }}"
        placeholder="pravila-diferenciranja"
        required
    >

    <div class="form-text">
        Jedinstven unutar tematske oblasti. Koristi mala slova i crtice.
    </div>

    @error('slug')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Opis lekcije</label>

    <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
    >{{ old('description', $lesson?->description) }}</textarea>

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="video_url" class="form-label">Video URL</label>

    <input
        type="url"
        id="video_url"
        name="video_url"
        class="form-control @error('video_url') is-invalid @enderror"
        value="{{ old('video_url', $lesson?->video_url) }}"
        placeholder="https://..."
    >

    <div class="form-text">
        Link ka videu na eksternoj video platformi.
        Za sada ne otpremamo video direktno na server.
    </div>

    @error('video_url')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="duration_minutes" class="form-label">
            Trajanje (minuti)
        </label>

        <input
            type="number"
            id="duration_minutes"
            name="duration_minutes"
            min="1"
            class="form-control @error('duration_minutes') is-invalid @enderror"
            value="{{ old('duration_minutes', $lesson?->duration_minutes) }}"
        >

        @error('duration_minutes')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="sort_order" class="form-label">
            Redosled prikaza
        </label>

        <input
            type="number"
            id="sort_order"
            name="sort_order"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $lesson?->sort_order ?? 0) }}"
            required
        >

        @error('sort_order')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="published_at" class="form-label">
        Datum i vreme objavljivanja
    </label>

    <input
        type="datetime-local"
        id="published_at"
        name="published_at"
        class="form-control @error('published_at') is-invalid @enderror"
        value="{{ old('published_at', $lesson?->published_at?->format('Y-m-d\TH:i')) }}"
    >

    <div class="form-text">
        Ostavi prazno za trenutno objavljivanje.
        Unesi budući datum ako želiš zakazano objavljivanje.
        Datum se tumači u vremenskoj zoni aplikacije.
    </div>

    @error('published_at')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <input type="hidden" name="is_free" value="0">

    <div class="form-check">
        <input
            type="checkbox"
            id="is_free"
            name="is_free"
            value="1"
            class="form-check-input"
            @checked(old('is_free', $lesson?->is_free ?? false))
        >

        <label for="is_free" class="form-check-label">
            Besplatna probna lekcija
        </label>
    </div>

    <div class="form-text">
        Besplatna lekcija biće dostupna i studentima bez kupljenog kursa,
        kada implementiramo javni prikaz lekcija.
    </div>
</div>

<div class="mb-4">
    <input type="hidden" name="is_published" value="0">

    <div class="form-check">
        <input
            type="checkbox"
            id="is_published"
            name="is_published"
            value="1"
            class="form-check-input"
            @checked(old('is_published', $lesson?->is_published ?? false))
        >

        <label for="is_published" class="form-check-label">
            Objavi lekciju
        </label>
    </div>

    <div class="form-text">
        Ako nije označeno, lekcija ostaje sakrivena bez obzira na datum.
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        Sačuvaj
    </button>

    <a
        href="{{ route('admin.lessons.index') }}"
        class="btn btn-outline-secondary"
    >
        Odustani
    </a>
</div>
