@extends('layouts.admin')

@section('title', 'Nova ponuda')
@section('page-title', 'Nova ponuda')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Kreiranje nove ponude</h5>
        </div>

        <div class="card-body">
            <form
                method="POST"
                action="{{ route('admin.offers.store') }}"
            >
                @csrf

                @include('admin.offers._form', [
                    'offer' => null,
                ])
            </form>
        </div>
    </div>
@endsection
