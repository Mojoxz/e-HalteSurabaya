@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="modern-dashboard">
    <!-- Page Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="title-section">
                <div class="title-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div>
                    <h1 class="page-title">Dashboard Admin</h1>
                    <p class="page-subtitle">Kelola sistem halte dengan mudah</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.haltes.create') }}" class="btn-modern btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Halte</span>
                </a>
                <a href="{{ route('home') }}" class="btn-modern btn-secondary">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Peta</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Total Halte</span>
                    <span class="stat-value">{{ $totalHaltes }}</span>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-bus"></i>
                </div>
            </div>
            <div class="stat-progress">
                <div class="progress-bar bg-primary"></div>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Tersedia</span>
                    <span class="stat-value">{{ $availableHaltes }}</span>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-progress">
                <div class="progress-bar bg-success"></div>
            </div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Disewa</span>
                    <span class="stat-value">{{ $rentedHaltes }}</span>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-progress">
                <div class="progress-bar bg-warning"></div>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Total Pendapatan</span>
                    <span class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-progress">
                <div class="progress-bar bg-info"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Quick Actions -->
        <div class="content-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Menu Utama</h3>
                    <p class="section-subtitle">Akses cepat ke fitur utama sistem</p>
                </div>
                <div class="actions-grid">
                    <div class="action-card">
                        <div class="action-icon bg-primary">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Kelola Halte</h4>
                            <p class="action-description">Lihat, tambah, edit, dan hapus data halte</p>
                            <a href="{{ route('admin.haltes.index') }}" class="action-btn">
                                Buka <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="action-card">
                        <div class="action-icon bg-success">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Tambah Halte</h4>
                            <p class="action-description">Tambahkan halte baru ke sistem</p>
                            <a href="{{ route('admin.haltes.create') }}" class="action-btn">
                                Tambah <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="action-card">
                        <div class="action-icon bg-info">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="action-content">
                            <h4 class="action-title">Riwayat Sewa</h4>
                            <p class="action-description">Lihat riwayat penyewaan halte</p>
                            <a href="{{ route('admin.rentals.index') }}" class="action-btn">
                                Lihat <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="sidebar-section">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Informasi Sistem</h3>
                </div>
                <div class="info-list">
                    <div class="info-group">
                        <h5 class="info-group-title">Versi</h5>
                        <div class="info-item">
                            <span class="info-label">Sistem:</span>
                            <span class="info-value">v1.0.0</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Laravel:</span>
                            <span class="info-value">{{ app()->version() }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">PHP:</span>
                            <span class="info-value">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Database:</span>
                            <span class="info-value">MySQL</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <h5 class="info-group-title">Pengguna</h5>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                                <span class="user-last-login">Login: {{ Auth::user()->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shortcuts -->
    <div class="shortcuts-section">
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">Shortcut Menu</h3>
                <p class="section-subtitle">Akses cepat ke semua fitur</p>
            </div>
            <div class="shortcuts-grid">
                <a href="{{ route('admin.haltes.index') }}" class="shortcut-btn">
                    <i class="fas fa-list"></i>
                    <span>Daftar Halte</span>
                </a>
                <a href="{{ route('admin.haltes.create') }}" class="shortcut-btn">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Halte</span>
                </a>
                <a href="{{ route('admin.rentals.index') }}" class="shortcut-btn">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Sewa</span>
                </a>
                <a href="{{ route('home') }}" class="shortcut-btn">
                    <i class="fas fa-map"></i>
                    <span>Lihat Peta</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.modern-dashboard {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 2rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header */
.dashboard-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.title-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.page-title {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.8);
    color: #374151;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-secondary:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    color: #374151;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.stat-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-success .stat-icon { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
.stat-warning .stat-icon { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
.stat-info .stat-icon { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

.stat-progress {
    height: 4px;
    background: #f3f4f6;
    border-radius: 2px;
    margin-top: 1rem;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 2px;
    width: 70%;
    animation: progressLoad 2s ease-in-out;
}

.bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-success { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
.bg-warning { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
.bg-info { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

@keyframes progressLoad {
    0% { width: 0; }
    100% { width: 70%; }
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

.section-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.section-header {
    margin-bottom: 2rem;
}

.section-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.section-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Actions Grid */
.actions-grid {
    display: grid;
    gap: 1.5rem;
}

.action-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.action-card:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.action-content {
    flex: 1;
}

.action-title {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.action-description {
    margin: 0 0 1rem 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.action-btn {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.action-btn:hover {
    color: #764ba2;
    gap: 0.75rem;
}

/* Info List */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-group-title {
    margin: 0 0 1rem 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.info-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1f2937;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.user-role {
    font-size: 0.75rem;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    width: fit-content;
    margin: 0.25rem 0;
}

.user-last-login {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Shortcuts */
.shortcuts-section {
    margin-bottom: 2rem;
}

.shortcuts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.shortcut-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 12px;
    text-decoration: none;
    color: #374151;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.shortcut-btn:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    color: #667eea;
}

.shortcut-btn i {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-dashboard {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
        justify-content: stretch;
    }

    .btn-modern {
        flex: 1;
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .shortcuts-grid {
        grid-template-columns: 1fr;
    }

    .page-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection
