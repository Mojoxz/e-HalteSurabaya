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
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

                <!-- Flash Messages -->
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
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

        // SweetAlert2 custom styling
        const swalCustomClass = {
            popup: 'rounded-4 shadow-lg',
            title: 'fw-bold',
            confirmButton: 'btn px-4 py-2',
            cancelButton: 'btn px-4 py-2',
            actions: 'gap-2'
        };

        // Auto show SweetAlert for flash messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                setTimeout(() => {
                    const successAlert = document.querySelector('.alert-success');
                    if (successAlert) {
                        const message = successAlert.textContent.trim().replace(/×/g, '').trim();
                        successAlert.remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: message,
                            confirmButtonColor: '#198754',
                            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                            timer: 3000,
                            timerProgressBar: true,
                            customClass: {
                                ...swalCustomClass,
                                confirmButton: 'btn btn-success px-4 py-2'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 100);
            @endif

            @if(session('error'))
                setTimeout(() => {
                    const errorAlert = document.querySelector('.alert-danger');
                    if (errorAlert) {
                        const message = errorAlert.textContent.trim().replace(/×/g, '').trim();
                        errorAlert.remove();

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: message,
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: '<i class="fas fa-times me-2"></i>OK',
                            customClass: {
                                ...swalCustomClass,
                                confirmButton: 'btn btn-danger px-4 py-2'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 100);
            @endif

            @if(session('warning'))
                setTimeout(() => {
                    const warningAlert = document.querySelector('.alert-warning');
                    if (warningAlert) {
                        const message = warningAlert.textContent.trim().replace(/×/g, '').trim();
                        warningAlert.remove();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: message,
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: '<i class="fas fa-exclamation-triangle me-2"></i>OK',
                            customClass: {
                                ...swalCustomClass,
                                confirmButton: 'btn btn-warning px-4 py-2'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 100);
            @endif

            @if(session('info'))
                setTimeout(() => {
                    const infoAlert = document.querySelector('.alert-info');
                    if (infoAlert) {
                        const message = infoAlert.textContent.trim().replace(/×/g, '').trim();
                        infoAlert.remove();

                        Swal.fire({
                            icon: 'info',
                            title: 'Informasi',
                            text: message,
                            confirmButtonColor: '#0dcaf0',
                            confirmButtonText: '<i class="fas fa-info-circle me-2"></i>OK',
                            customClass: {
                                ...swalCustomClass,
                                confirmButton: 'btn btn-info px-4 py-2'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 100);
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
