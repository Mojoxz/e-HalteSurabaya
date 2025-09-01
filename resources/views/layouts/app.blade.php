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
            --dishub-dark-blue: #153a73;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --gradient-primary: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
            --shadow-heavy: 0 8px 32px rgba(26, 75, 140, 0.16);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        .container {
            max-width: 1400px !important;
            width: 95% !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            margin: 0 auto;
        }

        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
        }

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

        .fade-in-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* MODAL LOGOUT STYLES - Disempurnakan */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            backdrop-filter: blur(3px);
            animation: modalFadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-popup {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            transform: scale(0.9);
            animation: modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .modal-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
            border: 3px solid;
        }

        .modal-icon.question {
            background: var(--dishub-light-blue);
            color: var(--dishub-blue);
            border-color: var(--dishub-accent);
        }

        .modal-icon.success {
            background: #f0fdf4;
            color: var(--success-color);
            border-color: var(--success-color);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .modal-message {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            background: #f8fafc;
        }

        /* Modal Buttons */
        .btn-modal {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 100px;
            justify-content: center;
            text-decoration: none;
        }

        .btn-modal:hover {
            transform: translateY(-1px);
        }

        .btn-modal:active {
            transform: translateY(0);
        }

        .btn-modal.btn-cancel {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-modal.btn-cancel:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-modal.btn-confirm {
            background: var(--dishub-blue);
            color: white;
        }

        .btn-modal.btn-confirm:hover {
            background: var(--dishub-dark-blue);
        }

        .btn-modal.btn-success {
            background: var(--dishub-blue);
            color: white;
        }

        .btn-modal.btn-success:hover {
            background: var(--dishub-dark-blue);
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-loading::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 16px;
            height: 16px;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Animations */
        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideIn {
            from {
                transform: scale(0.9) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -8px, 0);
            }
            70% {
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }

        .bounce {
            animation: bounce 1s ease;
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

        /* Modal Responsive */
        @media (max-width: 576px) {
            .modal-popup {
                width: 95%;
                margin: 0 10px;
            }

            .modal-header {
                padding: 1.5rem 1.5rem 1rem;
            }

            .modal-footer {
                padding: 1rem 1.5rem 1.5rem;
                flex-direction: column;
            }

            .btn-modal {
                width: 100%;
            }

            .modal-icon {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

            .modal-title {
                font-size: 1.1rem;
            }

            .modal-message {
                font-size: 0.9rem;
            }
        }

        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            width: 100%;
            max-width: none;
        }

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
                        <a class="nav-link" href="{{ route('gallery') }}">
                            <i class="fas fa-images me-1"></i> Gallery
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

            // Cek session success untuk menampilkan modal berhasil logout
            @if(session('success') && session('success') === 'Anda telah berhasil logout')
                showSuccessModal();
            @endif
        });

        // POPUP LOGOUT FUNCTIONS
        // Fungsi untuk menampilkan modal konfirmasi logout
        function showLogoutConfirmation() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('show');

            // Focus pada tombol "Batal" untuk accessibility
            setTimeout(() => {
                document.querySelector('.btn-cancel').focus();
            }, 100);
        }

        // Fungsi untuk membatalkan logout
        function cancelLogout() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('show');
        }

        // Fungsi untuk konfirmasi logout
        function confirmLogout() {
            const confirmBtn = document.getElementById('confirmBtn');
            const modal = document.getElementById('logoutModal');

            // Tampilkan loading state
            confirmBtn.classList.add('btn-loading');
            confirmBtn.innerHTML = '<span>Memproses...</span>';
            confirmBtn.disabled = true;

            // Buat form logout dan submit
            const logoutForm = document.createElement('form');
            logoutForm.method = 'POST';
            logoutForm.action = '{{ route("logout") }}';

            // Tambahkan CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            logoutForm.appendChild(csrfToken);

            // Tambahkan form ke body dan submit
            document.body.appendChild(logoutForm);
            logoutForm.submit();
        }

        // Fungsi untuk menampilkan modal berhasil logout
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('show');

            // Tambahkan bounce animation pada icon
            setTimeout(() => {
                const successIcon = document.querySelector('#successModal .modal-icon');
                if (successIcon) {
                    successIcon.classList.add('bounce');
                }
            }, 200);

            // Focus pada tombol untuk accessibility
            setTimeout(() => {
                const successBtn = document.querySelector('#successModal .btn-success');
                if (successBtn) {
                    successBtn.focus();
                }
            }, 100);
        }

        // Fungsi untuk redirect ke beranda
        function redirectToHome() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');

            setTimeout(() => {
                window.location.href = '{{ route("home") }}';
            }, 300);
        }

        // Event listener untuk ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const logoutModal = document.getElementById('logoutModal');
                const successModal = document.getElementById('successModal');

                if (logoutModal && logoutModal.classList.contains('show')) {
                    cancelLogout();
                } else if (successModal && successModal.classList.contains('show')) {
                    redirectToHome();
                }
            }
        });

        // Event listener untuk klik di overlay
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    if (overlay.id === 'logoutModal') {
                        cancelLogout();
                    } else if (overlay.id === 'successModal') {
                        redirectToHome();
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
