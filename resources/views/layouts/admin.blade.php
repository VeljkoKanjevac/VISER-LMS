
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Admin') | {{ config('app.name', 'Laravel') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        .admin-sidebar {
            min-height: calc(100vh - 56px);
        }

        .admin-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #495057;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }

        .admin-sidebar .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }

        .admin-sidebar .nav-link:hover {
            background-color: #f1f3f5;
            color: #0d6efd;
        }

        .admin-sidebar .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
        }

        .admin-sidebar .sidebar-heading {
            font-size: 0.7rem;
            letter-spacing: 0.08em;
        }

        .admin-sidebar hr {
            border-color: #dee2e6;
            opacity: 0.7;
            margin: 16px 0;
        }

        .admin-sidebar .nav-link.disabled-link {
            color: #adb5bd;
            cursor: not-allowed;
        }

        .admin-sidebar .nav-link.disabled-link:hover {
            background-color: transparent;
            color: #adb5bd;
        }

        @media (max-width: 767.98px) {
            .admin-sidebar {
                min-height: auto;
                border-bottom: 1px solid #dee2e6;
            }
        }
    </style>
</head>

<body class="bg-light">

{{-- Gornja navigacija --}}
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">

        <a
            class="navbar-brand d-flex align-items-center gap-2"
            href="{{ route('admin.dashboard') }}"
        >
            <i class="bi bi-mortarboard-fill"></i>
            <span>Admin panel</span>
        </a>

        <div class="d-flex align-items-center gap-3">

                <span class="text-white d-none d-sm-inline">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }}
                </span>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mb-0"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm"
                >
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Odjavi se
                </button>
            </form>

        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        {{-- Sidebar --}}
        <aside class="col-md-3 col-lg-2 bg-white border-end p-3 admin-sidebar">

            <nav class="nav flex-column gap-1">

                {{-- Dashboard --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                >
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <hr>

                {{-- Sadržaj --}}
                <div class="text-uppercase text-muted fw-bold px-3 mb-1 sidebar-heading">
                    Sadržaj
                </div>

                <a
                    href="{{ route('admin.faculties.index') }}"
                    class="nav-link {{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-buildings"></i>
                    <span>Fakulteti</span>
                </a>

                <a
                    href="{{ route('admin.courses.index') }}"
                    class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-book"></i>
                    <span>Kursevi</span>
                </a>

                <a
                    href="{{ route('admin.sections.index') }}"
                    class="nav-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-collection"></i>
                    <span>Sekcije</span>
                </a>

                <a
                    href="{{ route('admin.lessons.index') }}"
                    class="nav-link {{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-play-btn"></i>
                    <span>Lekcije</span>
                </a>

                <a
                    href="{{ route('admin.consultations.index') }}"
                    class="nav-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-camera-video"></i>
                    <span>Konsultacije</span>
                </a>

                <hr>

                {{-- Prodaja --}}
                <div class="text-uppercase text-muted fw-bold px-3 mb-1 sidebar-heading">
                    Prodaja
                </div>

                <a
                    href="{{ route('admin.offers.index') }}"
                    class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-tags"></i>
                    <span>Ponude</span>
                </a>

                {{-- Uplate - modul još nije implementiran --}}
                <span
                    class="nav-link disabled-link"
                    title="Modul uplata biće uskoro dostupan"
                >
                        <i class="bi bi-credit-card"></i>
                        <span>Uplate</span>
                        <i class="bi bi-lock ms-auto" style="font-size: 0.8rem;"></i>
                    </span>

                <hr>

                {{-- Referral --}}
                <div class="text-uppercase text-muted fw-bold px-3 mb-1 sidebar-heading">
                    Referral
                </div>

                <a
                    href="{{ route('admin.referral-codes.index') }}"
                    class="nav-link {{ request()->routeIs('admin.referral-codes.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-share"></i>
                    <span>Referral kodovi</span>
                </a>

                {{-- Provizije - modul još nije implementiran --}}
                <span
                    class="nav-link disabled-link"
                    title="Modul provizija biće uskoro dostupan"
                >
                        <i class="bi bi-cash-stack"></i>
                        <span>Provizije</span>
                        <i class="bi bi-lock ms-auto" style="font-size: 0.8rem;"></i>
                    </span>

                <hr>

                {{-- Korisnici --}}
                <a
                    href="{{ route('admin.users.index') }}"
                    class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-people"></i>
                    <span>Korisnici</span>
                </a>

            </nav>
        </aside>

        {{-- Glavni sadržaj --}}
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
