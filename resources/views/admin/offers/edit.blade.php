@extends('layouts.admin')

@section('title', 'Izmena ponude')
@section('page-title', 'Izmena ponude')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                Izmena ponude: {{ $offer->name }}
            </h5>
        </div>

        <div class="card-body">
            <form
                method="POST"
                action="{{ route('admin.offers.update', $offer) }}"
            >
                @csrf
                @method('PUT')

                @include('admin.offers._form')
            </form>
        </div>
    </div>
@endsection
