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
    <link rel="icon" type="image/svg" href="{{ asset('logo.svg') }}">

    @stack('styles')

    <style>
        :root {
            --dishub-blue: #1a4b8c;
            --dishub-light-blue: #e6f0fa;
            --dishub-accent: #2a75d6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
        }

        .navbar {
            background-color: var(--dishub-blue) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 4px;
            margin: 0 2px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link:focus {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--dishub-accent);
            border-color: var(--dishub-accent);
            font-weight: 500;
            padding: 0.5rem 1.25rem;
        }

        .btn-primary:hover {
            background-color: #1c65c7;
            border-color: #1c65c7;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: 500;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            font-weight: 500;
        }

        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--dishub-light-blue);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem 1.25rem;
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
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            color: #666;
            font-size: 0.9rem;
            padding: 1.5rem 0 !important;
        }

        .container {
            max-width: 1200px;
        }

        main {
            min-height: calc(100vh - 180px);
            padding: 2rem 0;
        }


                .navbar {
            background-color: var(--dishub-blue) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand-logo {
            height: 32px;
            width: auto;
            margin-right: 10px;
        }

        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--dishub-accent);
            box-shadow: 0 0 0 0.25rem rgba(42, 117, 214, 0.25);
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
    <footer class="py-4 mt-5">
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

    @stack('scripts')
</body>
</html>
