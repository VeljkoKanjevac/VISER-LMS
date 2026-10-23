
@extends('layouts.admin')

@section('title', 'Novi referral kod')
@section('page-title', 'Novi referral kod')

@section('content')

    {{-- Povratak --}}
    <div class="mb-4">
        <a
            href="{{ route('admin.referral-codes.index') }}"
            class="btn btn-outline-secondary"
        >
            &larr; Nazad na referral kodove
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        Dodeljivanje referral koda
                    </h5>
                </div>

                <div class="card-body">

                    <p class="text-muted mb-4">
                        Izaberi studenta kome želiš da dodeliš
                        referral kod. Sistem će automatski
                        generisati jedinstveni kod koji važi
                        za sve ponude.
                    </p>

                    {{-- Greške validacije --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.referral-codes.store') }}"
                    >
                        @csrf

                        {{-- Izbor studenta --}}
                        <div class="mb-4">
                            <label
                                for="user_id"
                                class="form-label fw-semibold"
                            >
                                Student
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="user_id"
                                id="user_id"
                                class="form-select @error('user_id') is-invalid @enderror"
                                required
                                @disabled($users->isEmpty())
                            >
                                <option value="">
                                    -- Izaberi studenta --
                                </option>

                                @foreach ($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        @selected(old('user_id') == $user->id)
                                    >
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>

                            @error('user_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Prikazani su samo studenti koji još
                                nemaju dodeljen referral kod.
                            </div>
                        </div>

                        {{-- Informacije --}}
                        <div class="alert alert-info mb-4">
                            <div class="fw-semibold mb-2">
                                Kako funkcioniše referral kod?
                            </div>

                            <ul class="mb-0">
                                <li>
                                    Svaki student može imati samo jedan kod.
                                </li>
                                <li>
                                    Kod važi za sve ponude.
                                </li>
                                <li>
                                    Popust i provizija zavise od ponude.
                                </li>
                                <li>
                                    Provizija se evidentira tek nakon
                                    potvrđene uplate.
                                </li>
                            </ul>
                        </div>

                        {{-- Dugmad --}}
                        <div class="d-flex flex-wrap justify-content-end gap-2">

                            <a
                                href="{{ route('admin.referral-codes.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Otkaži
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                @disabled($users->isEmpty())
                            >
                                Generiši referral kod
                            </button>

                        </div>
                    </form>

                </div>
            </div>

            {{-- Nema dostupnih studenata --}}
            @if ($users->isEmpty())
                <div class="alert alert-warning mt-3 mb-0">
                    Trenutno nema studenata kojima možeš
                    dodeliti novi referral kod.
                    Svi registrovani studenti već imaju kod
                    ili još nema registrovanih studenata.
                </div>
            @endif

        </div>
    </div>

@endsection
