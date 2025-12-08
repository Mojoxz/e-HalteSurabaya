@extends('layouts.app')

@section('title', 'Peta Halte Bus - E-HalteDishub')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Pass data to JavaScript -->
<script>
    // Make data available globally for maps.js
    window.isAdmin = @json(auth()->check() && auth()->user()->isAdmin());
    window.haltesData = @json($haltesData);
    window.isGuest = @json(!auth()->check());
    window.loginUrl = @json(route('login'));
</script>

<!-- Custom Maps JS -->
@vite(['resources/js/maps.js'])
@endpush
