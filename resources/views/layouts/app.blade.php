<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-HalteDishub')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/svg" href="{{ asset('logo1.svg') }}">

    @stack('styles')

    <style>
        :root {
            --dishub-blue: #1a4b8c;
            --dishub-light-blue: #e6f0fa;
            --dishub-accent: #2a75d6;
            --animation-timing: 0.3s;
        }

        /* Reset margin dan padding untuk full screen */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* Remove default body margins */
            margin: 0 !important;
            padding: 0 !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .navbar {
            background-color: var(--dishub-blue) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.5s ease-out;
            /* Make navbar stick to top edge */
            margin: 0;
            border-radius: 0;
            flex-shrink: 0;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white !important;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 4px;
            margin: 0 2px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after, .nav-link:focus::after {
            width: 70%;
        }

        .nav-link:hover, .nav-link:focus {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            animation: fadeInUp 0.2s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--dishub-light-blue);
            padding-left: 2rem;
        }

        .btn-primary {
            background-color: var(--dishub-accent);
            border-color: var(--dishub-accent);
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background-color: #1c65c7;
            border-color: #1c65c7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-success, .btn-danger {
            transition: all 0.3s ease;
        }

        .btn-success:hover, .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
            animation: fadeInScale 0.5s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .card:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--dishub-light-blue);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem 1.25rem;
            transition: all 0.3s ease;
        }

        .card:hover .card-header {
            background-color: #d8e6f5;
        }

        .table {
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: var(--dishub-light-blue);
            color: var(--dishub-blue);
            font-weight: 600;
            border-top: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .table tr {
            transition: all 0.2s ease;
        }

        .table tr:hover {
            background-color: rgba(230, 240, 250, 0.5);
            transform: scale(1.01);
        }

        .table td, .table th {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
        }

        .badge {
            font-size: 0.75em;
            font-weight: 500;
            padding: 0.35em 0.65em;
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        footer {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            color: #666;
            font-size: 0.9rem;
            padding: 1rem 0 !important;
            animation: fadeIn 0.8s ease-out;
            flex-shrink: 0;
            margin-top: auto;
        }

        /* Container yang lebih luas tapi tidak full screen */
        .container {
            max-width: 1400px !important;
            width: 95% !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            margin: 0 auto;
        }

        /* Container khusus untuk navbar agar tetap rapi */
        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Main content dengan padding yang lebih baik */
        main {
            flex: 1;
            padding: 2rem 0;
            animation: fadeInUpContent 0.7s ease-out;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        @keyframes fadeInUpContent {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-brand-logo {
            height: 32px;
            width: auto;
            margin-right: 10px;
            transition: all 0.5s ease;
        }

        .navbar-brand:hover .navbar-brand-logo {
            transform: rotate(10deg);
        }

        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--dishub-accent);
            box-shadow: 0 0 0 0.25rem rgba(42, 117, 214, 0.25);
            transform: translateY(-2px);
        }

        /* Animasi untuk elemen yang muncul saat di-scroll */
        .fade-in-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animasi untuk loading */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Media queries untuk responsivitas yang lebih balanced */
        @media (max-width: 768px) {
            .container {
                width: 95% !important;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .container {
                max-width: 1200px !important;
                width: 90% !important;
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 1400px !important;
                width: 85% !important;
            }
        }

        @media (min-width: 1440px) {
            .container {
                max-width: 1600px !important;
                width: 80% !important;
            }
        }

        /* Override bootstrap container untuk width yang balanced */
        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            width: 100%;
            max-width: none;
        }

        /* Class helper untuk content yang lebih lebar */
        .wider-content {
            width: 98%;
            max-width: 1600px;
            margin: 0 auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('pageatas.gif') }}" alt="Logo Animasi" width="50" height="50">
                <span>E-HalteDishub</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Sistem Manajemen Halte. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Dibuat dengan <span class="text-primary">Mojo</span> dan <span class="text-primary">Alfi</span> <i class="fas fa-heart text-danger"></i> menggunakan Hati</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Animasi untuk elemen yang muncul saat di-scroll
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in body
            document.body.style.opacity = 1;

            // Animasi untuk elemen dengan class fade-in-scroll
            const fadeElements = document.querySelectorAll('.fade-in-scroll');

            const fadeOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const fadeObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, fadeOptions);

            fadeElements.forEach(element => {
                fadeObserver.observe(element);
            });

            // Animasi hover untuk dropdown items
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
