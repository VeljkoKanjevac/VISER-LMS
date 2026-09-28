@extends('layouts.admin')

@section('title', 'Izmeni kurs')

@section('page-title', 'Izmeni kurs')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.courses.update', $course) }}"
                    >
                        @csrf
                        @method('PUT')

                        @include('admin.courses._form', [
                            'course' => $course,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
