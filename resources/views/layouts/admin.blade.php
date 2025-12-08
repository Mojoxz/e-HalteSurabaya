<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - E-HalteDishub')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/svg" href="{{ asset('logo1.svg') }}">

    <!-- Vite CSS -->
    @vite(['resources/css/admin.css'])

    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Include Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Admin Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h1>@yield('page-title', 'Dashboard Admin')</h1>
                </div>
                <div class="admin-header-actions">
                    <div class="admin-user-info">
                        <span>Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</span>
                        <div class="admin-user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i> Lihat Website
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item text-danger" onclick="showLogoutConfirmation()">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-area">
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <x-modal-confirm
        id="logoutModal"
        title="Yakin ingin keluar?"
        message="Apakah Anda yakin ingin keluar dari panel admin?"
        icon="fas fa-sign-out-alt"
        iconClass="icon-warning"
        cancelText="Batal"
        cancelAction="cancelLogout()"
        confirmText="Ya, Logout"
        confirmAction="confirmLogout()"
        confirmClass="btn-danger"
        confirmIcon="fas fa-sign-out-alt"
        confirmBtnId="confirmBtn"
    />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite JS -->
    @vite(['resources/js/admin.js'])

    <script>
        // Set logout route globally
        window.logoutRoute = "{{ route('logout') }}";

        // SweetAlert2 Flash Messages Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Success Alert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4CAF50',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Error Alert
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#f44336',
                    confirmButtonText: 'OK'
                });
            @endif

            // Warning Alert
            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#ff9800',
                    confirmButtonText: 'OK'
                });
            @endif

            // Info Alert
            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#2196F3',
                    confirmButtonText: 'OK'
                });
            @endif
        });

        // Logout Confirmation menggunakan SweetAlert2
        function showLogoutConfirmation() {
            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Apakah Anda yakin ingin keluar dari panel admin?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f44336',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Create and submit logout form
                    const logoutForm = document.createElement('form');
                    logoutForm.method = 'POST';
                    logoutForm.action = window.logoutRoute || '/logout';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    logoutForm.appendChild(csrfToken);

                    document.body.appendChild(logoutForm);
                    logoutForm.submit();
                }
            });
        }

        // Helper function untuk menampilkan SweetAlert dari JavaScript
        window.showAlert = function(type, title, message, timer = null) {
            const icons = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };

            const colors = {
                'success': '#4CAF50',
                'error': '#f44336',
                'warning': '#ff9800',
                'info': '#2196F3'
            };

            const config = {
                icon: icons[type] || 'info',
                title: title,
                text: message,
                confirmButtonColor: colors[type] || '#2196F3',
                confirmButtonText: 'OK'
            };

            if (timer) {
                config.timer = timer;
                config.timerProgressBar = true;
            }

            Swal.fire(config);
        };
    </script>

    @stack('scripts')
</body>
</html>
