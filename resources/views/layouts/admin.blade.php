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
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
        }

        /* Reset dan Base Styles */
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0 !important;
            padding: 0 !important;
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Admin Layout Specific Styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background-color: #f1f5f9;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all var(--animation-timing) ease;
            display: flex;
            flex-direction: column;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Admin Header */
        .admin-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: var(--header-height);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dishub-blue);
            margin: 0;
        }

        .admin-header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .admin-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--dishub-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
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

        /* Card Styles */
        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: all var(--transition);
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
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--dishub-light-blue);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem 1.25rem;
            color: var(--dishub-blue);
        }

        /* Button Styles */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background-color: var(--dishub-accent);
            border-color: var(--dishub-accent);
        }

        .btn-primary:hover {
            background-color: var(--dishub-dark-blue);
            border-color: var(--dishub-dark-blue);
            transform: translateY(-1px);
        }

        .btn-success:hover,
        .btn-danger:hover,
        .btn-warning:hover {
            transform: translateY(-1px);
        }

        /* Table Styles */
        .table {
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
        }

        .table th {
            background-color: var(--dishub-light-blue);
            color: var(--dishub-blue);
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(230, 240, 250, 0.3);
            transform: scale(1.005);
        }

        /* Alert Styles */
        .alert {
            border-radius: var(--border-radius);
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

        /* Form Styles */
        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            transition: all var(--transition);
            background-color: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--dishub-accent);
            box-shadow: 0 0 0 0.25rem rgba(42, 117, 214, 0.1);
            transform: translateY(-1px);
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75em;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all var(--transition);
            border-left: 4px solid var(--dishub-accent);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dishub-blue);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item a {
            color: var(--dishub-accent);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--dishub-dark-blue);
        }

        .breadcrumb-item.active {
            color: #6b7280;
        }

        /* Modal Logout Styles */
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
            background: var(--dishub-light-blue);
            color: var(--dishub-blue);
            border-color: var(--dishub-accent);
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }

            .admin-header {
                padding: 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .admin-header h1 {
                font-size: 1.25rem;
            }

            .admin-user-info {
                display: none;
            }

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
        }

        /* Loading States */
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

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Page Title */
        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title h2 {
            color: var(--dishub-blue);
            font-weight: 600;
            margin: 0;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Status Indicators */
        .status-online {
            color: var(--success-color);
        }

        .status-offline {
            color: #6b7280;
        }
    </style>
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
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-popup">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="modal-title">Konfirmasi Logout</h3>
                <p class="modal-message">
                    Apakah Anda yakin ingin keluar dari panel admin?
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in body
            document.body.style.opacity = 1;

            // Initialize main content adjustment for sidebar
            const mainContent = document.getElementById('mainContent');
            const sidebar = document.getElementById('adminSidebar');

            if (sidebar && mainContent) {
                // Check if sidebar is collapsed from localStorage
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    mainContent.classList.add('collapsed');
                }
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Add loading state to buttons when forms are submitted
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('btn-cancel')) {
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;
                    }
                });
            });
        });

        // Logout Modal Functions
        function showLogoutConfirmation() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('show');
            setTimeout(() => {
                document.querySelector('.btn-cancel').focus();
            }, 100);
        }

        function cancelLogout() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('show');
        }

        function confirmLogout() {
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.classList.add('btn-loading');
            confirmBtn.innerHTML = '<span>Memproses...</span>';
            confirmBtn.disabled = true;

            // Create and submit logout form
            const logoutForm = document.createElement('form');
            logoutForm.method = 'POST';
            logoutForm.action = '{{ route("logout") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            logoutForm.appendChild(csrfToken);

            document.body.appendChild(logoutForm);
            logoutForm.submit();
        }

        // Close modal with ESC key or overlay click
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const logoutModal = document.getElementById('logoutModal');
                if (logoutModal && logoutModal.classList.contains('show')) {
                    cancelLogout();
                }
            }
        });

        document.getElementById('logoutModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                cancelLogout();
            }
        });

        // Update main content when sidebar state changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'sidebarCollapsed') {
                const mainContent = document.getElementById('mainContent');
                if (mainContent) {
                    if (e.newValue === 'true') {
                        mainContent.classList.add('collapsed');
                    } else {
                        mainContent.classList.remove('collapsed');
                    }
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>