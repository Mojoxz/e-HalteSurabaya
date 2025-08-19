@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.haltes.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Halte
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-user-plus"></i> Tambah User
                </a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye"></i> Lihat Peta
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Halte</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Disewa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rentedHaltes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">User Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::active()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::admins()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Regular User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::users()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Menu Utama</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-list fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Kelola Halte</h5>
                                    <p class="card-text">Lihat, tambah, edit, dan hapus data halte.</p>
                                    <a href="{{ route('admin.haltes.index') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-right"></i> Buka
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Tambah Halte</h5>
                                    <p class="card-text">Tambahkan halte baru ke sistem.</p>
                                    <a href="{{ route('admin.haltes.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Tambah
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">Kelola User</h5>
                                    <p class="card-text">Manajemen user dan admin sistem.</p>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-warning">
                                        <i class="fas fa-users"></i> Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-history fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">Riwayat Sewa</h5>
                                    <p class="card-text">Lihat riwayat penyewaan halte.</p>
                                    <a href="{{ route('admin.rentals.index') }}" class="btn btn-info">
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Login</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-primary text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </div>
                        <h5>{{ Auth::user()->name }}</h5>
                        <span class="badge {{ Auth::user()->role_badge }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Login terakhir:</strong></td>
                            <td>{{ Auth::user()->last_login_formatted }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td>{{ ucfirst(Auth::user()->role) }}</td>
                        </tr>
                    </table>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Versi Sistem:</strong> v1.0.0<br>
                        <strong>Laravel:</strong> {{ app()->version() }}<br>
                        <strong>PHP:</strong> {{ PHP_VERSION }}<br>
                        <strong>Database:</strong> MySQL<br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity (jika ada) -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shortcut Menu</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-primary btn-block mb-2">
                                <i class="fas fa-list"></i> Daftar Halte
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.haltes.create') }}" class="btn btn-outline-success btn-block mb-2">
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
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}
.border-left-dark {
    border-left: 0.25rem solid #5a5c69 !important;
}
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
</style>
@endsection
