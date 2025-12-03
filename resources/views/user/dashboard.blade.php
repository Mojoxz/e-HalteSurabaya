@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard User')

@push('styles')
<style>
    /* Statistics Cards */
    .stats-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-color), var(--hover-color));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(108, 99, 255, 0.2);
        border-color: rgba(108, 99, 255, 0.3);
    }

    .stats-card:hover::before {
        opacity: 1;
    }

    .stats-card .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .stats-card.primary .card-icon {
        background: rgba(108, 99, 255, 0.15);
        color: var(--accent-color);
    }

    .stats-card.success .card-icon {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .stats-card.warning .card-icon {
        background: rgba(251, 146, 60, 0.15);
        color: #fb923c;
    }

    .stats-card h5 {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-card h2 {
        font-size: 36px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    /* Section Card */
    .section-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .section-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-card-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .section-card-body {
        padding: 24px;
    }

    /* Quick Action Buttons */
    .quick-action-btn {
        background: var(--primary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
        height: 100%;
    }

    .quick-action-btn:hover {
        background: var(--accent-dark);
        border-color: var(--accent-color);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(108, 99, 255, 0.2);
    }

    .quick-action-btn .icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        transition: all 0.3s ease;
    }

    .quick-action-btn.primary .icon {
        background: rgba(108, 99, 255, 0.15);
        color: var(--accent-color);
    }

    .quick-action-btn.success .icon {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .quick-action-btn.info .icon {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
    }

    .quick-action-btn:hover .icon {
        transform: scale(1.1);
    }

    .quick-action-btn strong {
        display: block;
        color: var(--text-primary);
        font-size: 16px;
        margin-bottom: 8px;
    }

    .quick-action-btn small {
        color: var(--text-secondary);
        font-size: 13px;
    }

    /* Halte Card */
    .halte-card {
        background: var(--primary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .halte-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border-color: rgba(108, 99, 255, 0.3);
    }

    .halte-card-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .halte-card-body {
        padding: 16px;
    }

    .halte-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .halte-card-text {
        color: var(--text-secondary);
        font-size: 13px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .halte-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Badge */
    .badge-custom {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-warning {
        background: rgba(251, 146, 60, 0.15);
        color: #fb923c;
        border: 1px solid rgba(251, 146, 60, 0.3);
    }

    /* Button */
    .btn-custom {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-custom-primary {
        background: rgba(108, 99, 255, 0.1);
        border: 1px solid rgba(108, 99, 255, 0.3);
        color: var(--accent-color);
    }

    .btn-custom-primary:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-1px);
    }

    .btn-custom-outline {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-secondary);
    }

    .btn-custom-outline:hover {
        background: var(--accent-color);
        border-color: var(--accent-color);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 16px;
        }

        .quick-action-btn {
            margin-bottom: 16px;
        }

        .halte-card {
            margin-bottom: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card primary">
                <div class="card-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <h5>Total Halte</h5>
                <h2>{{ $totalHaltes }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5>Halte Tersedia</h5>
                <h2>{{ $availableHaltes }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card warning">
                <div class="card-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <h5>Halte Disewa</h5>
                <h2>{{ $rentedHaltes }}</h2>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="section-card">
                <div class="section-card-header">
                    <h5><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="section-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('user.haltes.index') }}" class="quick-action-btn primary">
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <strong>Lihat Semua Halte</strong>
                                <small>Jelajahi daftar lengkap halte yang tersedia</small>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('user.map') }}" class="quick-action-btn success">
                                <div class="icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <strong>Lihat Peta</strong>
                                <small>Temukan lokasi halte di peta interaktif</small>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('user.profile') }}" class="quick-action-btn info">
                                <div class="icon">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <strong>Edit Profil</strong>
                                <small>Perbarui informasi akun Anda</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Haltes -->
    @if($recentHaltes->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="section-card">
                <div class="section-card-header">
                    <h5><i class="fas fa-clock me-2"></i>Halte Terbaru</h5>
                    <a href="{{ route('user.haltes.index') }}" class="btn-custom btn-custom-outline">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="section-card-body">
                    <div class="row">
                        @foreach($recentHaltes as $halte)
                        <div class="col-md-4">
                            <div class="halte-card">
                                @php
                                    $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                                    $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                                        ? asset('storage/' . $primaryPhoto->photo_path)
                                        : asset('images/halte-default.png');
                                @endphp
                                <img src="{{ $photoUrl }}" class="halte-card-img" alt="{{ $halte->name }}">
                                <div class="halte-card-body">
                                    <h6 class="halte-card-title">{{ $halte->name }}</h6>
                                    <p class="halte-card-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($halte->address, 50) }}</span>
                                    </p>
                                    @php
                                        $isRented = $halte->isCurrentlyRented();
                                    @endphp
                                    <div class="halte-card-footer">
                                        <span class="badge-custom {{ $isRented ? 'badge-warning' : 'badge-success' }}">
                                            {{ $isRented ? 'Disewa' : 'Tersedia' }}
                                        </span>
                                        <a href="{{ route('user.haltes.detail', $halte->id) }}" class="btn-custom btn-custom-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
