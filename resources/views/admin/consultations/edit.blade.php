@extends('layouts.admin')

@section('title', 'Izmena konsultacije')
@section('page-title', 'Izmena konsultacije')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">{{ $consultation->title }}</h5>
        </div>

        <div class="card-body">
            <form
                method="POST"
                action="{{ route('admin.consultations.update', $consultation) }}"
            >
                @csrf
                @method('PUT')

                @include('admin.consultations._form')
            </form>
        </div>
    </div>
@endsection
