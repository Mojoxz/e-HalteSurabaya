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

<!-- ✅ FIXED: Bootstrap Modal dengan struktur yang benar -->
<div class="modal fade" id="accessRestrictedModal" tabindex="-1" aria-labelledby="accessRestrictedModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; border: none;">
                <h5 class="modal-title" id="accessRestrictedModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Akses Terbatas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 40px 30px;">
                <div class="text-center">
                    <div class="mb-4">
                        <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);">
                            <i class="fas fa-lock" style="font-size: 36px; color: white;"></i>
                        </div>
                    </div>
                    <h4 class="mb-3" style="font-weight: 700; color: #1f2937;">Detail Halte Terbatas</h4>
                    <p class="text-muted mb-2" style="font-size: 16px; line-height: 1.6;">
                        Detail lengkap halte hanya dapat diakses oleh <strong style="color: #dc2626;">Admin yang terdaftar</strong>.
                    </p>
                    <p class="text-muted" style="font-size: 14px;">
                        Silakan login untuk melihat informasi detail halte bus.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 20px 30px; justify-content: center; gap: 12px;">
                @guest
                <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 14px 28px; border-radius: 12px; font-weight: 600; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);">
                    <i class="fas fa-sign-in-alt me-2"></i>Login sebagai Admin
                </a>
                @endguest
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 14px 28px; border-radius: 12px; font-weight: 600; background: #f3f4f6; color: #4b5563; border: 2px solid #e5e7eb;">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- ✅ Bootstrap JS (PENTING! Jangan dihapus) - Load SEBELUM maps.js -->
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
