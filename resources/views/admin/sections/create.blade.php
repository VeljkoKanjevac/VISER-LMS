@extends('layouts.admin')

@section('title', 'Dodaj tematsku oblast')

@section('page-title', 'Dodaj tematsku oblast')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.sections.store') }}"
                    >
                        @csrf

                        @include('admin.sections._form', [
                            'section' => null,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
