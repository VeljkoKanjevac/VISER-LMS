@extends('layouts.admin')

@section('title', 'Izmeni fakultet')

@section('page-title', 'Izmeni fakultet')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.faculties.update', $faculty) }}"
                    >
                        @csrf
                        @method('PUT')

                        @include('admin.faculties._form', [
                            'faculty' => $faculty,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
