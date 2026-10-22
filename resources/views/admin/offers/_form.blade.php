@php
    $selectedCourses = array_map('intval', old(
        'course_ids',
        $offer ? $offer->courses->pluck('id')->all() : []
    ));
@endphp

<div class="row g-3">

    {{-- Fakultet --}}
    <div class="col-md-6">
        <label for="faculty_id" class="form-label">Fakultet</label>

        <select
            name="faculty_id"
            id="faculty_id"
            class="form-select @error('faculty_id') is-invalid @enderror"
            required
        >
            <option value="">Izaberi fakultet</option>

            @foreach ($faculties as $faculty)
                <option
                    value="{{ $faculty->id }}"
                    @selected(old('faculty_id', $offer?->faculty_id) == $faculty->id)
                >
                    {{ $faculty->name }}
                </option>
            @endforeach
        </select>

        @error('faculty_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Naziv --}}
    <div class="col-md-6">
        <label for="name" class="form-label">Naziv ponude</label>

        <input
            type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $offer?->name) }}"
            placeholder="Npr. Paket VIŠER"
            required
        >

        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Slug --}}
    <div class="col-md-6">
        <label for="slug" class="form-label">Slug</label>

        <input
            type="text"
            name="slug"
            id="slug"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $offer?->slug) }}"
            placeholder="paket-viser"
            required
        >

        @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="form-text">
            Jedinstveni identifikator ponude, bez razmaka.
        </div>
    </div>

    {{-- Redosled --}}
    <div class="col-md-6">
        <label for="sort_order" class="form-label">Redosled prikaza</label>

        <input
            type="number"
            name="sort_order"
            id="sort_order"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $offer?->sort_order ?? 0) }}"
            required
        >

        @error('sort_order')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Opis --}}
    <div class="col-12">
        <label for="description" class="form-label">Opis ponude</label>

        <textarea
            name="description"
            id="description"
            rows="3"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Kratak opis onoga što ponuda uključuje..."
        >{{ old('description', $offer?->description) }}</textarea>

        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Cena --}}
    <div class="col-md-4">
        <label for="price" class="form-label">Cena (RSD)</label>

        <input
            type="number"
            name="price"
            id="price"
            min="0"
            class="form-control @error('price') is-invalid @enderror"
            value="{{ old('price', $offer?->price ?? 0) }}"
            required
        >

        @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Referral popust --}}
    <div class="col-md-4">
        <label for="referral_discount" class="form-label">
            Referral popust (RSD)
        </label>

        <input
            type="number"
            name="referral_discount"
            id="referral_discount"
            min="0"
            class="form-control @error('referral_discount') is-invalid @enderror"
            value="{{ old('referral_discount', $offer?->referral_discount ?? 0) }}"
            required
        >

        @error('referral_discount')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Referral provizija --}}
    <div class="col-md-4">
        <label for="referral_commission" class="form-label">
            Referral provizija (RSD)
        </label>

        <input
            type="number"
            name="referral_commission"
            id="referral_commission"
            min="0"
            class="form-control @error('referral_commission') is-invalid @enderror"
            value="{{ old('referral_commission', $offer?->referral_commission ?? 0) }}"
            required
        >

        @error('referral_commission')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kursevi --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            Kursevi uključeni u ponudu
        </label>

        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
            @forelse ($courses as $course)
                <div
                    class="form-check mb-2"
                    data-course-faculty="{{ $course->faculty_id }}"
                >
                    <input
                        type="checkbox"
                        name="course_ids[]"
                        value="{{ $course->id }}"
                        id="course-{{ $course->id }}"
                        class="form-check-input"
                        @checked(in_array((int) $course->id, $selectedCourses, true))
                    >

                    <label
                        for="course-{{ $course->id }}"
                        class="form-check-label"
                    >
                        {{ $course->faculty->short_name ?? $course->faculty->name }}
                        — {{ $course->name }}
                    </label>
                </div>
            @empty
                <p class="text-muted mb-0">
                    Nema dostupnih kurseva.
                </p>
            @endforelse
        </div>

        @error('course_ids')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        @error('course_ids.*')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <div class="form-text">
            Izaberi najmanje jedan kurs. Prikazuju se kursevi izabranog fakulteta.
        </div>
    </div>

</div>

<div class="d-flex gap-2 mt-4">
    <button
        type="submit"
        class="btn btn-primary"
        style="width: 110px;"
    >
        Sačuvaj
    </button>

    <a
        href="{{ route('admin.offers.index') }}"
        class="btn btn-outline-secondary"
        style="width: 110px;"
    >
        Odustani
    </a>
</div>

{{-- Filtriranje kurseva prema izabranom fakultetu --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const facultySelect = document.getElementById('faculty_id');
        const courseItems = document.querySelectorAll('[data-course-faculty]');

        function filterCourses() {
            const facultyId = facultySelect.value;

            courseItems.forEach(function (item) {
                const matches = facultyId !== '' &&
                    item.dataset.courseFaculty === facultyId;

                item.classList.toggle('d-none', !matches);

                const checkbox = item.querySelector('input[type="checkbox"]');

                if (!matches) {
                    checkbox.checked = false;
                }
            });
        }

        facultySelect.addEventListener('change', filterCourses);

        filterCourses();
    });
</script>
