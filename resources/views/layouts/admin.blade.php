<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') | {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            Admin panel
        </a>

        <div class="d-flex align-items-center gap-3">
                <span class="text-white">
                    {{ auth()->user()->name }}
                </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn btn-outline-light btn-sm">
                    Odjavi se
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-3 col-lg-2 bg-white border-end min-vh-100 p-3">
            <nav class="nav flex-column gap-1">

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="nav-link"
                >
                    Dashboard
                </a>

                <hr>

                <div class="text-uppercase text-muted small fw-bold px-3 mb-1">
                    Sadržaj
                </div>

                <a href="{{ route('admin.faculties.index') }}" class="nav-link">
                    Fakulteti
                </a>

                <a href="{{ route('admin.courses.index') }}" class="nav-link">
                    Kursevi
                </a>

                <a href="{{ route('admin.sections.index') }}" class="nav-link">
                    Sekcije
                </a>

                <a href="#" class="nav-link">
                    Lekcije
                </a>

                <a href="#" class="nav-link">
                    Konsultacije
                </a>

                <hr>

                <div class="text-uppercase text-muted small fw-bold px-3 mb-1">
                    Prodaja
                </div>

                <a href="#" class="nav-link">
                    Ponude
                </a>

                <a href="#" class="nav-link">
                    Uplate
                </a>

                <hr>

                <div class="text-uppercase text-muted small fw-bold px-3 mb-1">
                    Referral
                </div>

                <a href="#" class="nav-link">
                    Referral kodovi
                </a>

                <a href="#" class="nav-link">
                    Provizije
                </a>

                <hr>

                <a href="#" class="nav-link">
                    Korisnici
                </a>

            </nav>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    @yield('page-title')
                </h1>
            </div>

            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
