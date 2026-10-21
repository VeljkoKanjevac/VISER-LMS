@extends('layouts.admin')

@section('title', 'Nova konsultacija')
@section('page-title', 'Nova konsultacija')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Zakazivanje konsultacije</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.consultations.store') }}">
                @csrf

                @include('admin.consultations._form', [
                    'consultation' => null,
                ])
            </form>
        </div>
    </div>
@endsection
