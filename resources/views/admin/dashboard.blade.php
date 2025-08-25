@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-wrapper">
    <!-- Include Admin Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-content">
                <h1 class="page-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard Admin
                </h1>
                <div class="page-actions">
                    <button class="btn btn-dishub-primary btn-sm" id="refreshData">
                        <i class="fas fa-sync-alt"></i> Refresh Data
                    </button>
                </div>
            </div>
        </div>

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
                    <canvas id="statusChart" height="300"></canvas>
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
    </main>
</div>

<style>
:root {
    --dishub-blue: #1a4b8c;
    --dishub-light-blue: #e6f0fa;
    --dishub-accent: #2a75d6;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 70px;
    --header-height: 70px;
    --animation-timing: 0.3s;
}

/* Dashboard Layout */
.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    background-color: #f8fafc;
}

/* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, var(--dishub-blue) 0%, #153e75 100%);
    color: white;
    transition: all var(--animation-timing) ease;
    z-index: 1000;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    height: var(--header-height);
}

.sidebar-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    transition: opacity var(--animation-timing) ease;
}

.sidebar.collapsed .sidebar-text {
    opacity: 0;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all var(--animation-timing) ease;
}

.sidebar-toggle:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-content {
    padding: 1rem 0;
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

/* Navigation Styles */
.nav-section {
    margin-bottom: 2rem;
}

.nav-section-title {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.6);
    padding: 0 1rem;
    margin-bottom: 0.5rem;
    transition: opacity var(--animation-timing) ease;
}

.sidebar.collapsed .nav-section-title {
    opacity: 0;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all var(--animation-timing) ease;
    position: relative;
}

.nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
    text-decoration: none;
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.15);
    color: white;
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background-color: white;
}

.nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
    font-size: 1rem;
}

.nav-text {
    white-space: nowrap;
    transition: opacity var(--animation-timing) ease;
}

.sidebar.collapsed .nav-text {
    opacity: 0;
}

/* Main Content */
.main-content {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
    padding: 2rem;
    transition: all var(--animation-timing) ease;
}

.sidebar.collapsed + .main-content {
    margin-left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
}

/* Sidebar Show Button */
.sidebar-show-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: var(--dishub-blue);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(26, 75, 140, 0.3);
    cursor: pointer;
    transition: all var(--animation-timing) ease;
    font-size: 1.1rem;
}

.sidebar-show-btn:hover {
    background: #153e75;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(26, 75, 140, 0.4);
}

.sidebar-show-btn:active {
    transform: scale(0.95);
}

/* Animation for show button */
.sidebar-show-btn.show {
    animation: bounceIn 0.5s ease;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Page Header */
.page-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    margin-bottom: 2rem;
    animation: fadeInUp 0.5s ease;
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    color: var(--dishub-blue);
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
}

.page-actions .btn {
    transition: all var(--animation-timing) ease;
}

.page-actions .btn:hover {
    transform: translateY(-2px);
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    transition: all var(--animation-timing) ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--dishub-blue);
}

.stat-card.dishub-card-success::before {
    background: #28a745;
}

.stat-card.dishub-card-warning::before {
    background: #ffc107;
}

.stat-card.dishub-card-accent::before {
    background: var(--dishub-accent);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.8rem;
}

.stat-icon {
    font-size: 2.5rem;
    color: var(--dishub-blue);
    opacity: 0.3;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all var(--animation-timing) ease;
}

.analytics-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.analytics-header {
    background: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.analytics-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.analytics-actions .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}

.analytics-body {
    padding: 1.5rem;
}

/* Activity List */
.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.activity-desc {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: #adb5bd;
    font-size: 0.75rem;
}

/* User Stats */
.user-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.user-stat-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all var(--animation-timing) ease;
}

.user-stat-item:hover {
    background: #e9ecef;
    transform: scale(1.02);
}

.user-stat-icon {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.user-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.user-stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card, .analytics-card {
    animation: fadeInUp 0.6s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Sidebar Show Button */
.sidebar-show-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: var(--dishub-blue);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(26, 75, 140, 0.3);
    cursor: pointer;
    transition: all var(--animation-timing) ease;
    font-size: 1.1rem;
}

.sidebar-show-btn:hover {
    background: #153e75;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(26, 75, 140, 0.4);
}

.sidebar-show-btn:active {
    transform: scale(0.95);
}

/* Button Styles */
.btn-dishub-primary {
    background-color: var(--dishub-blue);
    border-color: var(--dishub-blue);
    color: white;
}

.btn-dishub-primary:hover {
    background-color: #153e75;
    border-color: #153e75;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-content {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
    }

    .page-header-content {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .main-content {
        padding: 1rem;
    }

    .user-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();

    // Refresh data functionality
    const refreshBtn = document.getElementById('refreshData');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');

            // Simulate data refresh
            setTimeout(() => {
                icon.classList.remove('fa-spin');
                // You can add actual refresh logic here
                console.log('Data refreshed');
            }, 2000);
        });
    }
});

function initializeCharts() {
    // Rental Chart
    const rentalCtx = document.getElementById('rentalChart');
    if (rentalCtx) {
        new Chart(rentalCtx, {
            type: 'line',
            data: {
                labels: ['1 Jan', '5 Jan', '10 Jan', '15 Jan', '20 Jan', '25 Jan', '30 Jan'],
                datasets: [{
                    label: 'Penyewaan',
                    data: [12, 19, 3, 5, 2, 3, 9],
                    borderColor: 'rgb(26, 75, 140)',
                    backgroundColor: 'rgba(26, 75, 140, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Disewa', 'Maintenance'],
                datasets: [{
                    data: [{{ $availableHaltes }}, {{ $rentedHaltes }}, 2],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
