@extends('layouts.admin')

@section('title', 'Dodaj lekciju')
@section('page-title', 'Dodaj lekciju')

@section('content')

    <div class="row">
        <div class="col-lg-9 col-xl-8">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.lessons.store') }}"
                    >
                        @csrf

                        @include('admin.lessons._form', [
                            'lesson' => null,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
