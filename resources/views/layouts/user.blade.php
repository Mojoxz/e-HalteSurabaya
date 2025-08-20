<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard User') - Sistem Informasi Halte</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            .sidebar {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar bg-dark" style="width: 250px; position: fixed; z-index: 1000;">
            <div class="p-3">
                <h5 class="text-white mb-0">Dashboard User</h5>
                <small class="text-muted">{{ Auth::user()->name }}</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('user.haltes.*') ? 'active' : '' }}" href="{{ route('user.haltes.index') }}">
                    <i class="fas fa-bus me-2"></i> Daftar Halte
                </a>
                <a class="nav-link {{ request()->routeIs('user.map') ? 'active' : '' }}" href="{{ route('user.map') }}">
                    <i class="fas fa-map me-2"></i> Peta Halte
                </a>
                <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                    <i class="fas fa-user me-2"></i> Profil
                </a>
                <hr class="text-white-50">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-globe me-2"></i> Kembali ke Halaman Utama
                </a>
                <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-fill">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-bottom p-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                <div>
                    <span class="me-3">Selamat datang, {{ Auth::user()->name }}</span>
                    <button class="btn btn-outline-danger btn-sm" onclick="document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
