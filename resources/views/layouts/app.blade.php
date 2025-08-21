<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-HalteDishub')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/svg" href="{{ asset('logo.svg') }}">

    @stack('styles')

    <style>
        :root {
            --dishub-blue: #1a4b8c;
            --dishub-light-blue: #e6f0fa;
            --dishub-accent: #2a75d6;
            --animation-timing: 0.3s;
            --container-padding: 1rem;
        }

        /* Base responsive font sizes */
        html {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            html {
                font-size: 14px;
            }
            :root {
                --container-padding: 0.75rem;
            }
        }

        @media (max-width: 400px) {
            html {
                font-size: 13px;
            }
            :root {
                --container-padding: 0.5rem;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
            overflow-x: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Navbar */
        .navbar {
            background-color: var(--dishub-blue) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.5s ease-out;
            padding: 0.75rem 0;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 0;
            }
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
            font-size: clamp(1rem, 4vw, 1.25rem);
            display: flex;
            align-items: center;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        /* Responsive logo */
        .navbar-brand-logo {
            height: clamp(24px, 5vw, 32px);
            width: auto;
            margin-right: clamp(8px, 2vw, 12px);
            transition: all 0.5s ease;
        }

        .navbar-brand:hover .navbar-brand-logo {
            transform: rotate(10deg);
        }

        /* Responsive Navigation Links */
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 4px;
            margin: 0 2px;
            transition: all 0.2s ease;
            position: relative;
            font-size: clamp(0.85rem, 2.5vw, 1rem);
        }

        @media (max-width: 991px) {
            .nav-link {
                padding: 0.75rem 0 !important;
                margin: 0.25rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-link:last-child {
                border-bottom: none;
            }
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

        @media (min-width: 992px) {
            .nav-link:hover::after, .nav-link:focus::after {
                width: 70%;
            }
        }

        .nav-link:hover, .nav-link:focus {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        @media (max-width: 991px) {
            .nav-link:hover, .nav-link:focus {
                transform: translateX(10px);
                background-color: rgba(255, 255, 255, 0.1);
                border-radius: 0;
            }
        }

        /* Responsive Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            animation: fadeInUp 0.2s ease-out;
            min-width: 180px;
        }

        @media (max-width: 991px) {
            .dropdown-menu {
                box-shadow: none;
                background-color: rgba(255, 255, 255, 0.95);
                margin-top: 0.5rem;
            }
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
            font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        }

        .dropdown-item:hover {
            background-color: var(--dishub-light-blue);
            padding-left: 2rem;
        }

        /* Responsive Buttons */
        .btn-primary {
            background-color: var(--dishub-accent);
            border-color: var(--dishub-accent);
            font-weight: 500;
            padding: clamp(0.375rem, 1.5vw, 0.5rem) clamp(0.75rem, 3vw, 1.25rem);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: clamp(0.8rem, 2.5vw, 1rem);
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

        @media (max-width: 576px) {
            .btn-primary:hover {
                transform: none;
            }
        }

        .btn-success, .btn-danger {
            transition: all 0.3s ease;
            font-size: clamp(0.8rem, 2.5vw, 1rem);
            padding: clamp(0.375rem, 1.5vw, 0.5rem) clamp(0.75rem, 3vw, 1rem);
        }

        .btn-success:hover, .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 576px) {
            .btn-success:hover, .btn-danger:hover {
                transform: none;
            }
        }

        /* Responsive Cards */
        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
            animation: fadeInScale 0.5s ease-out;
            margin-bottom: 1.5rem;
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

        @media (max-width: 768px) {
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 576px) {
            .card:hover {
                transform: none;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }
        }

        .card-header {
            background-color: var(--dishub-light-blue);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: clamp(0.75rem, 3vw, 1rem) clamp(1rem, 4vw, 1.25rem);
            transition: all 0.3s ease;
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        }

        .card:hover .card-header {
            background-color: #d8e6f5;
        }

        .card-body {
            padding: clamp(1rem, 4vw, 1.25rem);
        }

        /* Responsive Tables */
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table {
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
            border-collapse: separate;
            border-spacing: 0;
            font-size: clamp(0.8rem, 2.5vw, 1rem);
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--dishub-light-blue);
            color: var(--dishub-blue);
            font-weight: 600;
            border-top: 1px solid #dee2e6;
            transition: all 0.3s ease;
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            .table th {
                padding: 0.5rem 0.25rem;
                font-size: 0.7rem;
            }
        }

        .table tr {
            transition: all 0.2s ease;
        }

        .table tr:hover {
            background-color: rgba(230, 240, 250, 0.5);
            transform: scale(1.01);
        }

        @media (max-width: 768px) {
            .table tr:hover {
                transform: none;
            }
        }

        .table td, .table th {
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.5rem, 2vw, 1rem);
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
        }

        /* Responsive Badges */
        .badge {
            font-size: clamp(0.65em, 2vw, 0.75em);
            font-weight: 500;
            padding: clamp(0.25em, 1vw, 0.35em) clamp(0.5em, 2vw, 0.65em);
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        @media (max-width: 576px) {
            .badge:hover {
                transform: none;
            }
        }

        /* Responsive Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            animation: slideInRight 0.5s ease-out;
            font-size: clamp(0.85rem, 2.5vw, 1rem);
            padding: clamp(0.75rem, 3vw, 1rem) clamp(1rem, 4vw, 1.25rem);
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

        /* Responsive Footer */
        footer {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            color: #666;
            font-size: clamp(0.75rem, 2vw, 0.9rem);
            padding: clamp(1rem, 4vw, 1.5rem) 0 !important;
            animation: fadeIn 0.8s ease-out;
        }

        /* Responsive Container */
        .container {
            max-width: 1200px;
            padding-left: var(--container-padding);
            padding-right: var(--container-padding);
        }

        /* Responsive Main Content */
        main {
            min-height: calc(100vh - 180px);
            padding: clamp(1rem, 4vw, 2rem) 0;
            animation: fadeInUpContent 0.7s ease-out;
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

        /* Responsive Form Elements */
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: clamp(0.375rem, 2vw, 0.5rem) clamp(0.5rem, 2.5vw, 0.75rem);
            transition: all 0.3s ease;
            font-size: clamp(0.85rem, 2.5vw, 1rem);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--dishub-accent);
            box-shadow: 0 0 0 0.25rem rgba(42, 117, 214, 0.25);
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            .form-control:focus, .form-select:focus {
                transform: none;
            }
        }

        /* Responsive Text Utilities */
        @media (max-width: 576px) {
            .text-responsive {
                text-align: center !important;
            }

            .btn-responsive {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-sm-block {
                display: block !important;
            }
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

        /* Mobile menu improvements */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: var(--dishub-blue);
                margin-top: 1rem;
                border-radius: 8px;
                padding: 1rem 0;
            }

            .navbar-nav {
                text-align: center;
            }
        }

        /* Responsive spacing utilities */
        @media (max-width: 576px) {
            .p-responsive { padding: 0.5rem !important; }
            .m-responsive { margin: 0.5rem !important; }
            .mt-responsive { margin-top: 0.5rem !important; }
            .mb-responsive { margin-bottom: 0.5rem !important; }
        }

        /* Touch-friendly interactions */
        @media (hover: none) {
            .card:hover,
            .btn-primary:hover,
            .btn-success:hover,
            .btn-danger:hover,
            .table tr:hover,
            .badge:hover {
                transform: none;
            }
        }

        /* High DPI screens */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .navbar-brand-logo {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }

        /* Landscape orientation on mobile */
        @media (max-width: 896px) and (orientation: landscape) {
            main {
                min-height: calc(100vh - 120px);
                padding: 1rem 0;
            }

            .navbar {
                padding: 0.25rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('logo.svg') }}" alt="Logo E-HalteDishub" class="navbar-brand-logo">
                <span>E-HalteDishub</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                                    <i class="fas fa-tachometer-alt me-1"></i>
                                    <span class="d-lg-inline d-none">Dashboard Admin</span>
                                    <span class="d-lg-none">Admin</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>
                                <span class="d-lg-inline d-none">{{ Auth::user()->name }}</span>
                                <span class="d-lg-none">{{ Str::limit(Auth::user()->name, 10) }}</span>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-responsive">
                    <p class="mb-0">&copy; {{ date('Y') }} Sistem Manajemen Halte. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end text-responsive">
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
                    if (window.innerWidth > 991) {
                        this.style.transform = 'translateX(5px)';
                    }
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                const navbar = document.querySelector('.navbar-collapse');
                const toggler = document.querySelector('.navbar-toggler');

                if (navbar && navbar.classList.contains('show') &&
                    !navbar.contains(e.target) &&
                    !toggler.contains(e.target)) {
                    bootstrap.Collapse.getInstance(navbar).hide();
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            // Refresh any responsive calculations if needed
            const navbar = document.querySelector('.navbar-collapse');
            if (window.innerWidth > 991 && navbar && navbar.classList.contains('show')) {
                bootstrap.Collapse.getInstance(navbar).hide();
            }
        });

        // Performance optimization for touch devices
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
        }
    </script>

    @stack('scripts')
</body>
</html>
