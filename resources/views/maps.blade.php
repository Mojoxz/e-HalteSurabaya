@extends('layouts.app')

@section('title', 'Peta Halte Bus - E-HalteDishub')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Bootstrap Modal CSS (PENTING!) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom Maps CSS -->
@vite(['resources/css/maps.css'])
@endpush

@section('content')
<!-- Header Section -->
<section class="maps-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><i class="fas fa-map-marked-alt me-3"></i>Peta Lokasi Halte Bus</h1>
                <p>Jelajahi semua lokasi halte bus di Surabaya dengan peta interaktif dan fitur pencarian canggih</p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Bar -->
<section class="stats-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ $statistics['total'] }}</span>
                    <div class="stat-label"><i class="fas fa-bus me-1"></i>Total Halte</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item stat-available">
                    <span class="stat-number">{{ $statistics['available'] }}</span>
                    <div class="stat-label"><i class="fas fa-check-circle me-1"></i>Tersedia</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item stat-rented">
                    <span class="stat-number">{{ $statistics['rented'] }}</span>
                    <div class="stat-label"><i class="fas fa-clock me-1"></i>Disewa</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-container" id="mapSection">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="mapLoading">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Memuat peta dan data halte...</p>
        </div>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Search Control -->
    <div class="map-search-container">
        <div style="position: relative;">
            <input type="text"
                   class="map-search-input"
                   id="halteSearchInput"
                   placeholder="Cari halte berdasarkan nama, alamat, atau status..."
                   autocomplete="off">
            <button class="clear-search" id="clearSearch" title="Hapus pencarian">
                <i class="fas fa-times"></i>
            </button>
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="search-results" id="searchResults"></div>
    </div>

    <!-- Map Legend -->
    <div class="map-legend">
        <div class="legend-title">Keterangan</div>
        <div class="legend-item">
            <div class="legend-color legend-available"></div>
            <span>Halte Tersedia</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-rented"></div>
            <span>Halte Disewa</span>
        </div>
        <div class="legend-item">
            <div class="legend-color legend-user"></div>
            <span>Lokasi Anda</span>
        </div>
    </div>

    <!-- Fullscreen Toggle -->
    <button class="fullscreen-toggle" id="fullscreenToggle" title="Mode Layar Penuh">
        <i class="fas fa-expand"></i>
    </button>
</section>

<!-- Bootstrap Modal (SAMA seperti di home.blade.php) -->
<div class="modal fade" id="accessRestrictedModal" tabindex="-1" aria-labelledby="accessRestrictedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accessRestrictedModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Akses Terbatas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-lock" style="font-size: 4rem; color: #dc2626;"></i>
                    </div>
                    <h4 class="mb-3">Maaf, Akses Dibatasi!</h4>
                    <p class="text-muted">
                        Detail lengkap halte hanya dapat diakses oleh <strong>Admin yang terdaftar</strong>.
                        Silakan login untuk melihat informasi detail halte bus.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                @guest
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-1"></i> Login sebagai Admin
                </a>
                @endguest
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Bootstrap JS (PENTING! Jangan dihapus) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pass data to JavaScript -->
<script>
    // Make data available globally for maps.js
    window.isAdmin = @json(auth()->check() && auth()->user()->isAdmin());
    window.haltesData = @json($haltesData);
</script>

<!-- Custom Maps JS -->
@vite(['resources/js/maps.js'])
@endpush
