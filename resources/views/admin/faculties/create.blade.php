@extends('layouts.admin')

@section('title', 'Dodaj fakultet')

@section('page-title', 'Dodaj fakultet')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.faculties.store') }}"
                    >
                        @csrf

                        @include('admin.faculties._form', [
                            'faculty' => null,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
