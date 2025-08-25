@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-dishub-blue"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.haltes.create') }}" class="btn btn-sm btn-dishub-primary">
                    <i class="fas fa-plus"></i> Tambah Halte
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-dishub-success">
                    <i class="fas fa-user-plus"></i> Tambah User
                </a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dishub">
                    <i class="fas fa-eye"></i> Lihat Peta
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dishub-primary text-uppercase mb-1">Total Halte</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bus fa-2x text-dishub-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Disewa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rentedHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-accent shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dishub-accent text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-dishub-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-secondary-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">User Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::active()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-success-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dishub-primary text-uppercase mb-1">Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::admins()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-dishub-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dishub-card-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Regular User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::users()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-dark-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4 dishub-main-card">
                <div class="card-header py-3 dishub-header">
                    <h6 class="m-0 font-weight-bold text-white">Menu Utama</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 dishub-menu-card">
                                <div class="card-body">
                                    <i class="fas fa-list fa-3x text-dishub-primary mb-3"></i>
                                    <h5 class="card-title text-dishub-blue">Kelola Halte</h5>
                                    <p class="card-text">Lihat, tambah, edit, dan hapus data halte.</p>
                                    <a href="{{ route('admin.haltes.index') }}" class="btn btn-dishub-primary">
                                        <i class="fas fa-arrow-right"></i> Buka
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 dishub-menu-card">
                                <div class="card-body">
                                    <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                                    <h5 class="card-title text-dishub-blue">Tambah Halte</h5>
                                    <p class="card-text">Tambahkan halte baru ke sistem.</p>
                                    <a href="{{ route('admin.haltes.create') }}" class="btn btn-dishub-success">
                                        <i class="fas fa-plus"></i> Tambah
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 dishub-menu-card">
                                <div class="card-body">
                                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title text-dishub-blue">Kelola User</h5>
                                    <p class="card-text">Manajemen user dan admin sistem.</p>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-warning">
                                        <i class="fas fa-users"></i> Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 dishub-menu-card">
                                <div class="card-body">
                                    <i class="fas fa-history fa-3x text-dishub-accent mb-3"></i>
                                    <h5 class="card-title text-dishub-blue">Riwayat Sewa</h5>
                                    <p class="card-text">Lihat riwayat penyewaan halte.</p>
                                    <a href="{{ route('admin.rentals.index') }}" class="btn btn-dishub-accent">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4 dishub-profile-card">
                <div class="card-header py-3 dishub-header">
                    <h6 class="m-0 font-weight-bold text-white">Informasi Login</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-dishub-primary text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </div>
                        <h5 class="text-dishub-blue">{{ Auth::user()->name }}</h5>
                        <span class="badge dishub-role-badge">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong class="text-dishub-blue">Email:</strong></td>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-dishub-blue">Login terakhir:</strong></td>
                            <td>{{ Auth::user()->last_login_formatted }}</td>
                        </tr>
                        <tr>
                            <td><strong class="text-dishub-blue">Role:</strong></td>
                            <td>{{ ucfirst(Auth::user()->role) }}</td>
                        </tr>
                    </table>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-dishub btn-sm">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shortcut Menu -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 dishub-shortcut-card">
                <div class="card-header py-3 dishub-header">
                    <h6 class="m-0 font-weight-bold text-white">Shortcut Menu</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-dishub btn-block mb-2">
                                <i class="fas fa-list"></i> Daftar Halte
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.haltes.create') }}" class="btn btn-outline-dishub-accent btn-block mb-2">
                                <i class="fas fa-plus"></i> Tambah Halte
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning btn-block mb-2">
                                <i class="fas fa-users"></i> Kelola User
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-info btn-block mb-2">
                                <i class="fas fa-user-plus"></i> Tambah User
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                                <i class="fas fa-history"></i> Riwayat Sewa
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('home') }}" class="btn btn-outline-dark btn-block mb-2">
                                <i class="fas fa-map"></i> Lihat Peta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --dishub-blue: #1a4b8c;
    --dishub-light-blue: #e6f0fa;
    --dishub-accent: #2a75d6;
    --animation-timing: 0.3s;
    --container-padding: 1rem;
}

/* Dishub Color Classes */
.text-dishub-blue {
    color: var(--dishub-blue) !important;
}

.text-dishub-primary {
    color: var(--dishub-blue) !important;
}

.text-dishub-accent {
    color: var(--dishub-accent) !important;
}

.text-dishub-light {
    color: #a8c1e8 !important;
}

.text-success-light {
    color: #85e5b3 !important;
}

.text-warning-light {
    color: #f9d771 !important;
}

.text-secondary-light {
    color: #b1b5c2 !important;
}

.text-dark-light {
    color: #8a8d96 !important;
}

/* Button Styles */
.btn-dishub-primary {
    background-color: var(--dishub-blue);
    border-color: var(--dishub-blue);
    color: white;
    transition: all var(--animation-timing) ease;
}

.btn-dishub-primary:hover {
    background-color: #153e75;
    border-color: #153e75;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(26, 75, 140, 0.3);
}

.btn-dishub-accent {
    background-color: var(--dishub-accent);
    border-color: var(--dishub-accent);
    color: white;
    transition: all var(--animation-timing) ease;
}

.btn-dishub-accent:hover {
    background-color: #2463b8;
    border-color: #2463b8;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(42, 117, 214, 0.3);
}

.btn-dishub-success {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    transition: all var(--animation-timing) ease;
}

.btn-dishub-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-outline-dishub {
    color: var(--dishub-blue);
    border-color: var(--dishub-blue);
    transition: all var(--animation-timing) ease;
}

.btn-outline-dishub:hover {
    background-color: var(--dishub-blue);
    border-color: var(--dishub-blue);
    color: white;
    transform: translateY(-2px);
}

.btn-outline-dishub-accent {
    color: var(--dishub-accent);
    border-color: var(--dishub-accent);
    transition: all var(--animation-timing) ease;
}

.btn-outline-dishub-accent:hover {
    background-color: var(--dishub-accent);
    border-color: var(--dishub-accent);
    color: white;
    transform: translateY(-2px);
}

/* Card Styles */
.dishub-card-primary {
    border-left: 0.25rem solid var(--dishub-blue) !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(26, 75, 140, 0.2) !important;
}

.dishub-card-accent {
    border-left: 0.25rem solid var(--dishub-accent) !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-accent:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(42, 117, 214, 0.2) !important;
}

.dishub-card-success {
    border-left: 0.25rem solid #1cc88a !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(28, 200, 138, 0.2) !important;
}

.dishub-card-warning {
    border-left: 0.25rem solid #f6c23e !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(246, 194, 62, 0.2) !important;
}

.dishub-card-secondary {
    border-left: 0.25rem solid #858796 !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(133, 135, 150, 0.2) !important;
}

.dishub-card-dark {
    border-left: 0.25rem solid #5a5c69 !important;
    transition: all var(--animation-timing) ease;
}

.dishub-card-dark:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(90, 92, 105, 0.2) !important;
}

/* Special Card Styles */
.dishub-main-card,
.dishub-profile-card,
.dishub-shortcut-card {
    transition: all var(--animation-timing) ease;
    border: none;
    border-radius: 0.75rem;
}

.dishub-main-card:hover,
.dishub-profile-card:hover,
.dishub-shortcut-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.75rem 2.5rem 0 rgba(26, 75, 140, 0.15) !important;
}

.dishub-header {
    background: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
    border-radius: 0.75rem 0.75rem 0 0 !important;
}

.dishub-menu-card {
    transition: all var(--animation-timing) ease;
    border: 1px solid var(--dishub-light-blue);
    border-radius: 0.5rem;
}

.dishub-menu-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1.5rem 0 rgba(26, 75, 140, 0.15);
    border-color: var(--dishub-accent);
}

/* Role Badge */
.dishub-role-badge {
    background: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

/* General Styles */
.text-xs {
    font-size: .7rem;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.btn-block {
    width: 100%;
}

.avatar-lg {
    width: 64px;
    height: 64px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
}

.gap-2 {
    gap: 0.5rem;
}

/* Background and page styling */
body {
    background-color: var(--dishub-light-blue);
}

.container-fluid {
    padding: var(--container-padding);
}

/* Animations */
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

.card {
    animation: fadeIn 0.6s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-toolbar .btn-group {
        width: 100%;
    }

    .btn-group .btn {
        flex: 1;
        font-size: 0.8rem;
    }

    .dishub-menu-card {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
