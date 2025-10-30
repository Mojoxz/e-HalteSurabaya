@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card dishub-card-primary">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Total Halte</div>
                <div class="stat-value">{{ $totalHaltes }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="text-success">+2 bulan ini</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-bus"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-success">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Tersedia</div>
                <div class="stat-value">{{ $availableHaltes }}</div>
                <div class="stat-trend">
                    <span class="text-muted">{{ round(($availableHaltes/$totalHaltes)*100, 1) }}% dari total</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-warning">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Disewa</div>
                <div class="stat-value">{{ $rentedHaltes }}</div>
                <div class="stat-trend">
                    <span class="text-muted">{{ round(($rentedHaltes/$totalHaltes)*100, 1) }}% dari total</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-accent">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="text-success">+15% bulan ini</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="analytics-grid">
    <!-- Rental Chart -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-chart-line"></i>
                Statistik Penyewaan (30 Hari Terakhir)
            </h5>
            <div class="analytics-actions">
                <select class="form-select form-select-sm">
                    <option value="30">30 Hari</option>
                    <option value="60">60 Hari</option>
                    <option value="90">90 Hari</option>
                </select>
            </div>
        </div>
        <div class="analytics-body">
            <canvas id="rentalChart" height="300"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-chart-pie"></i>
                Distribusi Status Halte
            </h5>
        </div>
        <div class="analytics-body">
            <canvas id="statusChart"
                    height="300"
                    data-available="{{ $availableHaltes }}"
                    data-rented="{{ $rentedHaltes }}"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-clock"></i>
                Aktivitas Terbaru
            </h5>
        </div>
        <div class="analytics-body">
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon bg-success">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Halte Baru Ditambahkan</div>
                        <div class="activity-desc">Halte Suramadu berhasil ditambahkan</div>
                        <div class="activity-time">2 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-warning">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Penyewaan Baru</div>
                        <div class="activity-desc">Halte Taman Bungkul disewa oleh PT ABC</div>
                        <div class="activity-time">4 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">User Baru</div>
                        <div class="activity-desc">John Doe mendaftar sebagai user</div>
                        <div class="activity-time">6 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-primary">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Data Halte Diperbarui</div>
                        <div class="activity-desc">Informasi Halte Wonokromo diperbarui</div>
                        <div class="activity-time">8 jam yang lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-users"></i>
                Statistik User
            </h5>
        </div>
        <div class="analytics-body">
            <div class="user-stats">
                <div class="user-stat-item">
                    <div class="user-stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::count() }}</div>
                        <div class="user-stat-label">Total User</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::active()->count() }}</div>
                        <div class="user-stat-label">User Aktif</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-warning">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::admins()->count() }}</div>
                        <div class="user-stat-label">Admin</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-secondary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::users()->count() }}</div>
                        <div class="user-stat-label">Regular User</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    @vite(['resources/css/dashboard-admin.css'])
@endpush

@push('scripts')
    @vite(['resources/js/dashboard-admin.js'])
@endpush
