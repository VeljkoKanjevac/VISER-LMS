@extends('layouts.admin')

@section('title', 'Izmeni lekciju')
@section('page-title', 'Izmeni lekciju')

@section('content')

    <div class="row">
        <div class="col-lg-9 col-xl-8">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.lessons.update', $lesson) }}"
                    >
                        @csrf
                        @method('PUT')

                        @include('admin.lessons._form', [
                            'lesson' => $lesson,
                        ])
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
