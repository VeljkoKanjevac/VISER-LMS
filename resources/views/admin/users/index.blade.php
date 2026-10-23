
@extends('layouts.admin')

@section('title', 'Korisnici')
@section('page-title', 'Korisnici')

@section('content')

    {{-- Statistika --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        Ukupno korisnika
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['total_users'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        Ukupno studenata
                    </div>

                    <h3 class="mb-0">
                        {{ $statistics['total_students'] }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Pretraga i filteri --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form
                method="GET"
                action="{{ route('admin.users.index') }}"
                class="row g-3 align-items-end"
            >
                <div class="col-md-6">
                    <label for="search" class="form-label">
                        Pretraga korisnika
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        value="{{ $search }}"
                        placeholder="Ime ili email adresa..."
                    >
                </div>

                <div class="col-md-3">
                    <label for="role" class="form-label">
                        Uloga
                    </label>

                    <select
                        name="role"
                        id="role"
                        class="form-select"
                    >
                        <option value="">Sve uloge</option>

                        <option
                            value="student"
                            @selected($role === 'student')
                        >
                            Student
                        </option>

                        <option
                            value="admin"
                            @selected($role === 'admin')
                        >
                            Administrator
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button
                            type="submit"
                            class="btn btn-primary flex-fill"
                        >
                            Pretraži
                        </button>

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn btn-outline-secondary flex-fill"
                        >
                            Resetuj
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela korisnika --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista korisnika</h5>

            <span class="text-muted small">
                Pronađeno: {{ $users->total() }}
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Korisnik</th>
                        <th>Uloga</th>
                        <th>Upisi</th>
                        <th>Uplate</th>
                        <th>Referral kod</th>
                        <th>Registrovan</th>
                        <th class="text-end">Akcije</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($users as $user)
                        <tr>
                            {{-- Korisnik --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $user->name }}
                                </div>

                                <div class="text-muted small">
                                    {{ $user->email }}
                                </div>
                            </td>

                            {{-- Uloga --}}
                            <td>
                                @if ($user->role === 'admin')
                                    <span class="badge bg-primary">
                                            Administrator
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            Student
                                        </span>
                                @endif
                            </td>

                            {{-- Upisi --}}
                            <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $user->enrollments_count }}
                                    </span>
                            </td>

                            {{-- Uplate --}}
                            <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $user->payments_count }}
                                    </span>
                            </td>

                            {{-- Referral kod --}}
                            <td>
                                @if ($user->referral_code_count > 0)
                                    <span class="badge bg-success">
                                            Dodeljen
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            Nema kod
                                        </span>
                                @endif
                            </td>

                            {{-- Datum registracije --}}
                            <td class="text-nowrap">
                                {{ $user->created_at->format('d.m.Y.') }}
                            </td>

                            {{-- Akcije --}}
                            <td class="text-end">
                                <a
                                    href="{{ route('admin.users.show', $user) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    style="width: 110px;"
                                >
                                    Detalji
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="text-center text-muted py-5"
                            >
                                Nema korisnika koji odgovaraju pretrazi.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginacija --}}
        @if ($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
