
@extends('layouts.admin')

@section('title', 'Zahtevi za upis')

@section('page-title', 'Zahtevi za upis')


@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">
                    Pregled svih zahteva koje su studenti poslali.
                </p>
            </div>

            <span class="badge bg-primary fs-6">
            Ukupno: {{ $requests->count() }}
        </span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Student</th>
                            <th>Ponuda</th>
                            <th>Iznos</th>
                            <th>Referral kod</th>
                            <th>Status</th>
                            <th>Datum zahteva</th>
                            <th class="text-end pe-3">Akcije</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td class="ps-3 fw-semibold">
                                    #{{ $request->id }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $request->user?->name ?? 'Nepoznat student' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $request->user?->email }}
                                    </div>
                                </td>

                                <td>
                                    {{ $request->offer?->name ?? 'Nepoznata ponuda' }}
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ number_format($request->amount, 0, ',', '.') }}
                                        RSD
                                    </div>

                                    @if($request->discount_amount > 0)
                                        <div class="text-success small">
                                            Popust:
                                            {{ number_format($request->discount_amount, 0, ',', '.') }}
                                            RSD
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($request->referralCode)
                                        <span class="badge bg-info text-dark">
                                            {{ $request->referralCode->code }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($request->isPending())
                                        <span class="badge bg-warning text-dark">
                                            Na čekanju
                                        </span>
                                    @elseif($request->isConfirmed())
                                        <span class="badge bg-success">
                                            Potvrđen
                                        </span>
                                    @elseif($request->isRejected())
                                        <span class="badge bg-danger">
                                            Odbijen
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $request->created_at?->format('d.m.Y. H:i') }}
                                </td>

                                <td class="text-end pe-3">
                                    @if($request->isPending())

                                        <div class="d-flex justify-content-end gap-2">

                                            <form
                                                action="{{ route('admin.enrollment-requests.confirm', $request->id) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Da li je uplata proverena i želite da potvrdite ovaj zahtev?')"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Potvrdi
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('admin.enrollment-requests.reject', $request->id) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Da li želite da odbijete ovaj zahtev?')"
                                                >
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Odbij
                                                </button>
                                            </form>

                                        </div>

                                    @else

                                        <span class="text-muted small">
                                         Obrađen
                                        </span>

                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8 " class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Trenutno nema zahteva za upis.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
