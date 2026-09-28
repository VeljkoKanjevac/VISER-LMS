@extends('layouts.admin')

@section('title', 'Izmeni tematsku oblast')

@section('page-title', 'Izmeni tematsku oblast')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-xl-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.sections.update', $section) }}"
                    >
                        @csrf
                        @method('PUT')

                        @include('admin.sections._form', [
                            'section' => $section,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
