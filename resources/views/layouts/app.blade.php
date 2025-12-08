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

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
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
                        <a class="nav-link" href="{{ route('gallery') }}">
                            <i class="fas fa-images me-1"></i> Gallery
                        </a>

                        <a class="nav-link" href="{{ route('maps') }}">
                            <i class="fas fa-map me-1"></i> Maps
                        </a>
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
                                    <button type="button" class="dropdown-item" onclick="showLogoutConfirmation()">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </button>
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

    <!-- Modal Konfirmasi Logout -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-popup">
            <div class="modal-header">
                <div class="modal-icon question">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="modal-title">Logout</h3>
                <p class="modal-message">
                    Apakah Anda yakin untuk logout?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-cancel" onclick="cancelLogout()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button class="btn-modal btn-confirm" onclick="confirmLogout()" id="confirmBtn">
                    <i class="fas fa-check"></i>
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Berhasil Logout -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-popup">
            <div class="modal-header">
                <div class="modal-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="modal-title">Logout Berhasil</h3>
                <p class="modal-message">
                    Anda telah berhasil logout dari sistem.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-success" onclick="redirectToHome()">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])

    <script>
        // Set Laravel routes for JavaScript
        window.logoutRoute = '{{ route("logout") }}';
        window.homeRoute = '{{ route("home") }}';
        window.csrfToken = '{{ csrf_token() }}';

        // Cek session success untuk menampilkan modal berhasil logout
        @if(session('success') && session('success') === 'Anda telah berhasil logout')
            showSuccessModal();
        @endif
    </script>

    @stack('scripts')
</body>
</html> 
