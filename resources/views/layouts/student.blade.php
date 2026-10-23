
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Studentski portal') | EDU Platform
    </title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f6f8fc;
            color: #1e293b;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .student-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e8edf4;
        }

        .student-brand {
            font-size: 1.35rem;
            font-weight: 800;
            color: #2563eb;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .student-brand:hover {
            color: #1d4ed8;
        }

        .student-navbar .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            transition: 0.2s;
        }

        .student-navbar .nav-link:hover {
            background: #f1f5f9;
            color: #2563eb;
        }

        .student-navbar .nav-link.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .student-main {
            min-height: calc(100vh - 150px);
        }

        .student-page-title {
            font-weight: 750;
            letter-spacing: -0.6px;
        }

        .student-card {
            background: #ffffff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.035);
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .student-footer {
            border-top: 1px solid #e8edf4;
            background: #ffffff;
            color: #94a3b8;
            font-size: 0.875rem;
        }
    </style>

    @stack('styles')
</head>

<body>

<!-- NAVIGACIJA -->
<nav class="navbar navbar-expand-lg student-navbar sticky-top">
    <div class="container py-2">

        <a
            class="student-brand"
            href="{{ route('student.offers.index') }}"
        >
            <i class="bi bi-mortarboard-fill me-2"></i>
            EDU Platform
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#studentNavbar"
            aria-controls="studentNavbar"
            aria-expanded="false"
            aria-label="Otvori navigaciju"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="studentNavbar"
        >

            <ul class="navbar-nav mx-auto gap-lg-2">

                <!-- Dostupne ponude -->
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('student.offers.*') ? 'active' : '' }}"
                        href="{{ route('student.offers.index') }}"
                    >
                        <i class="bi bi-grid me-1"></i>
                        Ponude
                    </a>
                </li>

                <!-- Moji zahtevi -->
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('student.enrollment-requests.*') ? 'active' : '' }}"
                        href="{{ route('student.enrollment-requests.index') }}"
                    >
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Moji zahtevi
                    </a>
                </li>

            </ul>

            @auth
                <div class="dropdown mt-3 mt-lg-0">

                    <button
                        class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="bi bi-person-circle fs-5"></i>

                        <span>
                                {{ auth()->user()->name }}
                            </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                        <li>
                            <a
                                class="dropdown-item"
                                href="{{ route('profile.edit') }}"
                            >
                                <i class="bi bi-person me-2"></i>
                                Moj profil
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item text-danger"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Odjavi se
                                </button>
                            </form>
                        </li>

                    </ul>

                </div>
            @endauth

        </div>
    </div>
</nav>

<!-- GLAVNI SADRŽAJ -->
<main class="student-main">
    <div class="container py-4 py-lg-5">

        @hasSection('page-title')
            <div class="mb-4">
                <h1 class="student-page-title h3 mb-0">
                    @yield('page-title')
                </h1>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Zatvori"
                ></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </div>
</main>

<!-- FOOTER -->
<footer class="student-footer py-4">
    <div class="container d-flex flex-wrap justify-content-between gap-2">

            <span>
                &copy; {{ date('Y') }} EDU Platform
            </span>

        <span>
                Uči pametnije. Polaži sigurnije.
            </span>

    </div>
</footer>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

@stack('scripts')

</body>
</html>
