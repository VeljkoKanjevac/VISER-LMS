@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <div class="row g-4">

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Fakulteti
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $statistics['faculties_count'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Kursevi
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $statistics['courses_count'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Ponude
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $statistics['offers_count'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Studenti
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $statistics['users_count'] }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
