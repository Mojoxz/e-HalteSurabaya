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

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Menu Utama</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
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
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Versi Sistem:</strong> v1.0.0<br>
                        <strong>Laravel:</strong> {{ app()->version() }}<br>
                        <strong>PHP:</strong> {{ PHP_VERSION }}<br>
                        <strong>Database:</strong> MySQL<br>
                    </div>
                    <div class="mb-3">
                        <strong>Login sebagai:</strong><br>
                        <i class="fas fa-user-shield"></i> {{ Auth::user()->name }}<br>
                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    <div class="mb-0">
                        <strong>Login terakhir:</strong><br>
                        {{ Auth::user()->updated_at->format('d/m/Y H:i') }}
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
                        <div class="col-md-3">
                            <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-primary btn-block mb-2">
                                <i class="fas fa-list"></i> Daftar Halte
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.haltes.create') }}" class="btn btn-outline-success btn-block mb-2">
                                <i class="fas fa-plus"></i> Tambah Halte
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-info btn-block mb-2">
                                <i class="fas fa-history"></i> Riwayat Sewa
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-block mb-2">
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
</style>
@endsection
