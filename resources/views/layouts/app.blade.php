<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Manajemen Halte')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    @stack('styles')

    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #06b6d4;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Modern Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--gray-800) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link i {
            margin-right: 0.5rem;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: var(--gray-800);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .dropdown-item button {
            background: none;
            border: none;
            color: inherit;
            font-weight: inherit;
            width: 100%;
            text-align: left;
        }

        /* Modern Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin: 1rem;
            font-weight: 500;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(6, 182, 212, 0.1));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(245, 158, 11, 0.1));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert i {
            margin-right: 0.5rem;
        }

        /* Modern Button Styles */
        .btn {
            font-weight: 600;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), var(--secondary-color));
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), var(--warning-color));
            color: white;
        }

        .btn-outline-primary {
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Modern Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(6, 182, 212, 0.05));
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .card-body {
            padding: 2rem;
        }

        /* Modern Table Styles */
        .table {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            font-weight: 600;
            color: var(--gray-800);
            border: none;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(79, 70, 229, 0.02);
        }

        /* Modern Badge Styles */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .bg-success {
            background: linear-gradient(135deg, var(--success-color), #34d399) !important;
        }

        .bg-danger {
            background: linear-gradient(135deg, var(--danger-color), #fb7185) !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, var(--info-color), #60a5fa) !important;
        }

        /* Modern Footer */
        footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.1);
            margin-top: 4rem;
        }

        footer p {
            margin: 0;
            color: var(--gray-800);
            font-weight: 500;
        }

        footer .text-danger {
            color: var(--danger-color) !important;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .table th,
            .table td {
                padding: 0.75rem;
            }
        }

        /* Loading Spinner */
        .spinner-border {
            width: 2rem;
            height: 2rem;
            border-width: 0.2em;
        }

        /* Focus States */
        .btn:focus,
        .nav-link:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        /* Hover Effects for Interactive Elements */
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Main Content Container */
        main {
            min-height: calc(100vh - 200px);
            padding: 1rem 0;
        }

        /* Container Enhancements */
        .container,
        .container-fluid {
            position: relative;
        }

        /* Modern Form Styles */
        .form-control,
        .form-select {
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-bus"></i> Smart Halte System
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 100px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} Smart Halte Management System. All rights reserved.</p>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-top: 0.5rem;">
                        Powered by modern web technologies & innovative design
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Dikembangkan dengan <i class="fas fa-heart text-danger"></i> oleh</p>
                    <p style="font-weight: 700; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Tim Developer Mojo & Alfi
                    </p>
                </div>
            </div>
            <hr style="margin: 1.5rem 0; border-color: rgba(255, 255, 255, 0.2);">
            <div class="row">
                <div class="col-12 text-center">
                    <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; color: var(--gray-600); font-size: 0.875rem;">
                        <span><i class="fas fa-shield-alt"></i> Secure & Reliable</span>
                        <span><i class="fas fa-mobile-alt"></i> Mobile Responsive</span>
                        <span><i class="fas fa-clock"></i> Real-time Updates</span>
                        <span><i class="fas fa-database"></i> SIMBADA Integrated</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Add fade-in animation to main content
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.classList.add('fade-in');
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading state to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.type === 'submit' || this.closest('form')) {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                        this.disabled = true;

                        // Re-enable button after 3 seconds (fallback)
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }, 3000);
                    }
                });
            });
        });

        // Add navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
