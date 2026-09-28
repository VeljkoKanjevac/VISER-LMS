<div class="mb-3">
    <label for="name" class="form-label">
        Naziv fakulteta
    </label>

    <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $faculty?->name) }}"
        required
    >

    @error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-3">
    <label for="short_name" class="form-label">
        Skraćeni naziv
    </label>

    <input
        type="text"
        id="short_name"
        name="short_name"
        class="form-control @error('short_name') is-invalid @enderror"
        value="{{ old('short_name', $faculty?->short_name) }}"
    >

    @error('short_name')
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
        value="{{ old('slug', $faculty?->slug) }}"
        required
    >

    @error('slug')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror

    <div class="form-text">
        Primer: viser, fon, etf
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
    >{{ old('description', $faculty?->description) }}</textarea>

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
        value="{{ old('sort_order', $faculty?->sort_order ?? 0) }}"
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
            @checked(old('is_active', $faculty?->is_active ?? true))
        >

        <label
            for="is_active"
            class="form-check-label"
        >
            Fakultet je aktivan
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
        href="{{ route('admin.faculties.index') }}"
        class="btn btn-outline-secondary"
    >
        Odustani
    </a>
</div>
