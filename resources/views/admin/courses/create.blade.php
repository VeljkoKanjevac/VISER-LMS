@extends('layouts.admin')

@section('title', 'Dodaj kurs')

@section('page-title', 'Dodaj kurs')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.courses.store') }}"
                    >
                        @csrf

                        @include('admin.courses._form', [
                            'course' => null,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
