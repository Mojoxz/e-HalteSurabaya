@extends('layouts.app')

@section('title', 'Peta Halte Bus - E-HalteDishub')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Bootstrap Modal CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --accent-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        --dishub-blue: #1e3c72;
        --dishub-accent: #3b82f6;
    }

    body {
        background-color: #f8fafc;
        margin: 0;
        padding: 0;
    }

    /* Header Section */
    .maps-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem 0 1rem 0;
        box-shadow: 0 4px 20px rgba(30, 60, 114, 0.3);
    }

    .maps-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .maps-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Stats Bar */
    .stats-bar {
        background: white;
        padding: 1.5rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-bottom: 1px solid #e2e8f0;
    }

    .stat-item {
        text-align: center;
        padding: 0 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dishub-blue);
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-available .stat-number {
        color: #059669;
    }

    .stat-rented .stat-number {
        color: #dc2626;
    }

    /* Map Container */
    .map-container {
        height: calc(100vh - 280px);
        min-height: 600px;
        position: relative;
        margin: 0;
        padding: 0;
    }

    #map {
        height: 100%;
        width: 100%;
        border: none;
        border-radius: 0;
        box-shadow: none;
    }

    /* Map Controls */
    .map-search-container {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        padding: 12px;
        min-width: 350px;
    }

    .map-search-input {
        border: 2px solid #e5e7eb;
        outline: none;
        width: 100%;
        padding: 12px 45px 12px 15px;
        font-size: 14px;
        border-radius: 10px;
        background-color: #f9fafb;
        transition: all 0.3s ease;
    }

    .map-search-input:focus {
        background-color: #fff;
        border-color: var(--dishub-accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        font-size: 16px;
    }

    .clear-search {
        position: absolute;
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 16px;
        display: none;
        padding: 5px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .clear-search:hover {
        color: #6b7280;
        background-color: #f3f4f6;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1001;
        display: none;
        margin-top: 5px;
    }

    .search-result-item {
        padding: 15px 18px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        background-color: #f8fafc;
    }

    .search-result-item:last-child {
        border-bottom: none;
        border-radius: 0 0 10px 10px;
    }

    .search-result-item:first-child {
        border-radius: 10px 10px 0 0;
    }

    .search-result-item.active {
        background-color: #eff6ff;
        border-left: 4px solid var(--dishub-accent);
    }

    .search-result-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .search-result-info {
        font-size: 12px;
        color: #6b7280;
    }

    .no-results {
        padding: 20px;
        text-align: center;
        color: #9ca3af;
        font-style: italic;
        font-size: 14px;
    }

    /* Map Legend */
    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 200px;
    }

    .legend-title {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .legend-item:last-child {
        margin-bottom: 0;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 12px;
        border: 3px solid white;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }

    .legend-available { 
        background-color: #059669; 
    }
    
    .legend-rented { 
        background-color: #dc2626; 
    }

    .legend-user {
        background-color: #2563eb;
    }

    /* Popup Styles */
    .leaflet-popup-content {
        width: 380px !important;
        margin: 0 !important;
    }

    .leaflet-popup-content-wrapper {
        padding: 0 !important;
        border-radius: 15px !important;
    }

    .popup-photo-container {
        position: relative;
        width: 100%;
        height: 220px;
        margin-bottom: 15px;
        border-radius: 15px 15px 0 0;
        overflow: hidden;
        background: #f8f9fa;
    }

    .popup-photo {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: none;
    }

    .popup-photo.active {
        display: block;
    }

    .popup-photo-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        transition: all 0.2s ease;
    }

    .popup-photo-nav:hover {
        background: rgba(0,0,0,0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .popup-photo-nav.prev {
        left: 15px;
    }

    .popup-photo-nav.next {
        right: 15px;
    }

    .popup-photo-counter {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .no-photos {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-style: italic;
        height: 100%;
        flex-direction: column;
    }

    .no-photos i {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .popup-content {
        padding: 0;
    }

    .popup-info {
        padding: 20px;
    }

    .popup-title {
        font-size: 1.2em;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1f2937;
    }

    .popup-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 700;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-available {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-rented {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .popup-details {
        font-size: 0.9em;
        line-height: 1.6;
    }

    .info-row {
        margin-bottom: 8px;
        display: flex;
        align-items: flex-start;
    }

    .info-label {
        font-weight: 600;
        color: #4b5563;
        min-width: 100px;
        margin-right: 10px;
    }

    .info-value {
        color: #1f2937;
        flex: 1;
    }

    .popup-actions {
        padding: 15px 20px;
        background-color: #f8fafc;
        border-top: 1px solid #e5e7eb;
        text-align: center;
        border-radius: 0 0 15px 15px;
    }

    .btn-detail {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 25px;
        font-size: 0.9em;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    .btn-detail.disabled {
        background: linear-gradient(135deg, #94a3b8 0%, #cbd5e1 100%);
        cursor: not-allowed;
        opacity: 0.7;
        box-shadow: 0 4px 15px rgba(148, 163, 184, 0.2);
    }

    .btn-detail.disabled:hover {
        transform: none;
        box-shadow: 0 4px 15px rgba(148, 163, 184, 0.2);
    }

    /* Loading Overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }

    .loading-content {
        text-align: center;
        color: var(--dishub-blue);
    }

    .loading-content .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 1rem;
    }

    /* Marker Animation */
    .highlighted-marker {
        animation: pulse 1.5s ease-in-out 3;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.4);
            opacity: 0.6;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .maps-header h1 {
            font-size: 2rem;
        }
        
        .maps-header p {
            font-size: 1rem;
        }

        .map-search-container {
            left: 10px;
            right: 10px;
            min-width: auto;
            max-width: calc(100vw - 20px);
        }

        .map-legend {
            bottom: 10px;
            right: 10px;
            left: 10px;
            padding: 15px;
        }

        .map-container {
            height: calc(100vh - 320px);
        }

        .stat-item {
            padding: 0 0.5rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .leaflet-popup-content {
            width: 300px !important;
        }
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
    }

    .modal-header .btn-close {
        filter: invert(1);
    }

    .modal-body {
        padding: 2.5rem;
        text-align: center;
    }

    .modal-icon {
        font-size: 4rem;
        color: #f59e0b;
        margin-bottom: 1.5rem;
    }

    .modal-title-custom {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .modal-text {
        color: #6b7280;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1.5rem 2rem;
        background-color: #f8fafc;
        border-radius: 0 0 20px 20px;
    }

    .btn-login-modal {
        background: var(--secondary-gradient);
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(29, 78, 216, 0.3);
    }

    .btn-login-modal:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(29, 78, 216, 0.4);
        color: white;
    }

    .btn-understand {
        background: var(--accent-gradient);
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }

    .btn-understand:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
    }

    /* Full screen toggle button */
    .fullscreen-toggle {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--dishub-blue);
    }

    .fullscreen-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        background: var(--dishub-blue);
        color: white;
    }

    /* Fullscreen mode */
    .fullscreen-map {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
    }

    .fullscreen-map #map {
        height: 100vh !important;
    }
</style>
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

<!-- Access Restricted Modal -->
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
                <div class="modal-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="modal-title-custom">
                    Maaf, Akses Dibatasi!
                </div>
                <div class="modal-text">
                    Detail lengkap halte hanya dapat diakses oleh <strong>Admin yang terdaftar</strong>.
                    Silakan login untuk melihat informasi detail halte bus.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                @guest
                <a href="{{ route('login') }}" class="btn btn-login-modal me-2">
                    <i class="fas fa-sign-in-alt me-1"></i> Login sebagai Admin
                </a>
                @endguest
                <button type="button" class="btn btn-understand" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i> Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Check if user is admin
    const isAdmin = @json(auth()->check() && auth()->user()->isAdmin());

    // Initialize map centered on Surabaya, East Java
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: true
    }).setView([-7.2575, 112.7521], 12);

    // Add OpenStreetMap tiles with better styling
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | E-HalteDishub',
        maxZoom: 19,
        tileSize: 256,
        zoomOffset: 0
    }).addTo(map);

    // Halte data from Laravel
    const haltesData = @json($haltesData);

    // Create custom marker icons with better styling
    const availableIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #059669; width: 24px; height: 24px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(5, 150, 105, 0.5); position: relative;"><div style="position: absolute; top: -2px; left: -2px; width: 28px; height: 28px; border: 2px solid #059669; border-radius: 50%; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    const rentedIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #dc2626; width: 24px; height: 24px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(220, 38, 38, 0.5); position: relative;"><div style="position: absolute; top: -2px; left: -2px; width: 28px; height: 28px; border: 2px solid #dc2626; border-radius: 50%; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    // Add CSS for ping animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Store markers and halte data for search functionality
    const markers = {};
    const searchData = [];

    // Function to create photo carousel HTML
    function createPhotoCarousel(photos, halteName) {
        if (!photos || photos.length === 0) {
            return `
                <div class="popup-photo-container">
                    <div class="no-photos">
                        <i class="fas fa-camera"></i>
                        <span>Tidak ada foto tersedia</span>
                    </div>
                </div>
            `;
        }

        let carouselHtml = '<div class="popup-photo-container">';

        // Add photos
        photos.forEach((photo, index) => {
            carouselHtml += `
                <img src="${photo}"
                    alt="${halteName}"
                    class="popup-photo ${index === 0 ? 'active' : ''}"
                    data-index="${index}"
                    loading="lazy"
                    onerror="this.style.display='none'">
            `;
        });

        // Add navigation buttons if more than one photo
        if (photos.length > 1) {
            carouselHtml += `
                <button class="popup-photo-nav prev" onclick="changePhoto(this, -1)" title="Foto sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="popup-photo-nav next" onclick="changePhoto(this, 1)" title="Foto selanjutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="popup-photo-counter">
                    <span class="current-photo">1</span>/<span class="total-photos">${photos.length}</span>
                </div>
            `;
        }

        carouselHtml += '</div>';
        return carouselHtml;
    }

    // Global function to change photos (accessible from onclick)
    window.changePhoto = function(button, direction) {
        const container = button.closest('.popup-photo-container');
        const photos = container.querySelectorAll('.popup-photo');
        const counter = container.querySelector('.current-photo');

        let currentIndex = -1;
        photos.forEach((photo, index) => {
            if (photo.classList.contains('active')) {
                currentIndex = index;
            }
            photo.classList.remove('active');
        });

        let newIndex = currentIndex + direction;
        if (newIndex >= photos.length) {
            newIndex = 0;
        } else if (newIndex < 0) {
            newIndex = photos.length - 1;
        }

        photos[newIndex].classList.add('active');
        if (counter) {
            counter.textContent = newIndex + 1;
        }
    };

    // Function to handle detail button click
    window.handleDetailClick = function(halteId, event) {
        event.preventDefault();

        if (isAdmin) {
            // Admin can access detail page directly
            window.location.href = `/halte/${halteId}/detail`;
        } else {
            // Show modal for non-admin users
            const modal = new bootstrap.Modal(document.getElementById('accessRestrictedModal'));
            modal.show();
        }
    };

    // Add markers for each halte
    haltesData.forEach(function(halte) {
        const icon = halte.rental_status === 'rented' ? rentedIcon : availableIcon;

        // Create photo carousel
        const photoCarousel = createPhotoCarousel(halte.photos, halte.name);

        // Create detail button with conditional behavior
        const detailButton = isAdmin
            ? `<a href="/halte/${halte.id}/detail" class="btn-detail">
                <i class="fas fa-info-circle me-1"></i> Lihat Detail Lengkap
               </a>`
            : `<button onclick="handleDetailClick(${halte.id}, event)" class="btn-detail">
                <i class="fas fa-info-circle me-1"></i> Lihat Detail Lengkap
               </button>`;

        // Create popup content with carousel and conditional detail button
        let popupContent = `
            <div class="popup-content">
                ${photoCarousel}
                <div class="popup-info">
                    <div class="popup-title">${halte.name}</div>
                    <div class="popup-status ${halte.rental_status === 'rented' ? 'status-rented' : 'status-available'}">
                        <i class="fas fa-${halte.rental_status === 'rented' ? 'clock' : 'check-circle'} me-1"></i>
                        ${halte.rental_status === 'rented' ? 'DISEWA' : 'TERSEDIA'}
                    </div>
                    <div class="popup-details">
                        ${halte.description ? `<div class="info-row"><span class="info-label">Deskripsi:</span> <span class="info-value">${halte.description}</span></div>` : ''}
                        ${halte.address ? `<div class="info-row"><span class="info-label">Alamat:</span> <span class="info-value">${halte.address}</span></div>` : ''}
                        ${halte.is_rented && halte.rented_by ? `<div class="info-row"><span class="info-label">Disewa oleh:</span> <span class="info-value">${halte.rented_by}</span></div>` : ''}
                        ${halte.is_rented && halte.rent_end_date ? `<div class="info-row"><span class="info-label">Sewa sampai:</span> <span class="info-value">${halte.rent_end_date}</span></div>` : ''}
                        ${halte.simbada_registered ? `<div class="info-row"><span class="info-label">SIMBADA:</span> <span class="info-value"><span style="background-color: #059669; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.75em; font-weight: 600;">TERDAFTAR</span></span></div>` : ''}
                        ${halte.simbada_number ? `<div class="info-row"><span class="info-label">No. SIMBADA:</span> <span class="info-value">${halte.simbada_number}</span></div>` : ''}
                        <div class="info-row">
                            <span class="info-label">Koordinat:</span> 
                            <span class="info-value">${halte.latitude.toFixed(6)}, ${halte.longitude.toFixed(6)}</span>
                        </div>
                    </div>
                </div>
                <div class="popup-actions">
                    ${detailButton}
                </div>
            </div>
        `;

        // Create marker
        const marker = L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 400,
                className: 'custom-popup',
                closeButton: true,
                autoPan: true,
                autoPanPadding: [50, 50]
            })
            .addTo(map);

        // Store marker for search functionality
        markers[halte.id] = marker;

        // Prepare search data
        searchData.push({
            id: halte.id,
            name: halte.name,
            address: halte.address || '',
            description: halte.description || '',
            status: halte.rental_status === 'rented' ? 'Disewa' : 'Tersedia',
            simbada: halte.simbada_registered ? 'Terdaftar' : 'Tidak Terdaftar',
            rented_by: halte.rented_by || '',
            latitude: halte.latitude,
            longitude: halte.longitude,
            marker: marker
        });
    });

    // Search functionality
    const searchInput = document.getElementById('halteSearchInput');
    const searchResults = document.getElementById('searchResults');
    const clearButton = document.getElementById('clearSearch');
    let searchTimeout;

    // Function to normalize text for search
    function normalizeText(text) {
        return text.toLowerCase()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .trim();
    }

    // Function to highlight marker
    function highlightMarker(markerId) {
        const marker = markers[markerId];
        if (marker) {
            const markerElement = marker._icon;
            if (markerElement) {
                markerElement.classList.add('highlighted-marker');
                setTimeout(() => {
                    markerElement.classList.remove('highlighted-marker');
                }, 4500);
            }
        }
    }

    // Function to perform search
    function performSearch(query) {
        const normalizedQuery = normalizeText(query);
        
        if (normalizedQuery.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        const results = searchData.filter(halte => {
            return normalizeText(halte.name).includes(normalizedQuery) ||
                   normalizeText(halte.address).includes(normalizedQuery) ||
                   normalizeText(halte.description).includes(normalizedQuery) ||
                   normalizeText(halte.status).includes(normalizedQuery) ||
                   normalizeText(halte.simbada).includes(normalizedQuery) ||
                   normalizeText(halte.rented_by).includes(normalizedQuery);
        });

        displaySearchResults(results);
    }

    // Function to display search results
    function displaySearchResults(results) {
        if (results.length === 0) {
            searchResults.innerHTML = '<div class="no-results"><i class="fas fa-search me-2"></i>Tidak ada halte yang ditemukan</div>';
            searchResults.style.display = 'block';
            return;
        }

        let resultsHtml = '';
        results.slice(0, 8).forEach(halte => { // Increase limit to 8 results
            const statusColor = halte.status === 'Disewa' ? '#dc2626' : '#059669';
            const statusIcon = halte.status === 'Disewa' ? 'clock' : 'check-circle';
            
            resultsHtml += `
                <div class="search-result-item" data-halte-id="${halte.id}">
                    <div class="search-result-name">
                        <i class="fas fa-map-marker-alt me-2" style="color: ${statusColor}"></i>
                        ${halte.name}
                    </div>
                    <div class="search-result-info">
                        ${halte.address ? halte.address + ' • ' : ''}
                        <span style="color: ${statusColor}">
                            <i class="fas fa-${statusIcon} me-1"></i>
                            ${halte.status}
                        </span>
                    </div>
                </div>
            `;
        });

        if (results.length > 8) {
            resultsHtml += `
                <div class="search-result-item" style="font-style: italic; color: #6b7280; text-align: center; padding: 10px;">
                    <i class="fas fa-ellipsis-h me-2"></i>
                    dan ${results.length - 8} hasil lainnya
                </div>
            `;
        }

        searchResults.innerHTML = resultsHtml;
        searchResults.style.display = 'block';

        // Add click handlers to search results
        searchResults.querySelectorAll('.search-result-item[data-halte-id]').forEach(item => {
            item.addEventListener('click', function() {
                const halteId = this.dataset.halteId;
                const halte = searchData.find(h => h.id == halteId);
                
                if (halte) {
                    // Center map on halte with smooth animation
                    map.flyTo([halte.latitude, halte.longitude], 17, {
                        duration: 1.5
                    });
                    
                    // Highlight and open popup after animation
                    highlightMarker(halteId);
                    setTimeout(() => {
                        halte.marker.openPopup();
                    }, 1500);
                    
                    // Hide search results
                    searchResults.style.display = 'none';
                    
                    // Update search input with selected halte name
                    searchInput.value = halte.name;
                    clearButton.style.display = 'block';
                }
            });
        });
    }

    // Search input event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            searchResults.style.display = 'none';
            clearButton.style.display = 'none';
            return;
        }
        
        clearButton.style.display = 'block';
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Clear search functionality
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        searchResults.style.display = 'none';
        clearButton.style.display = 'none';
        searchInput.focus();
    });

    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-container')) {
            searchResults.style.display = 'none';
        }
    });

    // Show search results when input is focused and has value
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            performSearch(this.value.trim());
        }
    });

    // Keyboard navigation for search results
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.querySelectorAll('.search-result-item[data-halte-id]');
        const currentActive = searchResults.querySelector('.search-result-item.active');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentActive) {
                currentActive.classList.remove('active');
                const next = currentActive.nextElementSibling;
                if (next && next.dataset.halteId) {
                    next.classList.add('active');
                } else if (items.length > 0) {
                    items[0].classList.add('active');
                }
            } else if (items.length > 0) {
                items[0].classList.add('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentActive) {
                currentActive.classList.remove('active');
                const prev = currentActive.previousElementSibling;
                if (prev && prev.dataset.halteId) {
                    prev.classList.add('active');
                } else if (items.length > 0) {
                    items[items.length - 1].classList.add('active');
                }
            } else if (items.length > 0) {
                items[items.length - 1].classList.add('active');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentActive && currentActive.dataset.halteId) {
                currentActive.click();
            }
        } else if (e.key === 'Escape') {
            searchResults.style.display = 'none';
            this.blur();
        }
    });

    // Add hover effect for keyboard navigation
    searchResults.addEventListener('mouseover', function(e) {
        if (e.target.closest('.search-result-item[data-halte-id]')) {
            // Remove active class from all items
            this.querySelectorAll('.search-result-item.active').forEach(item => {
                item.classList.remove('active');
            });
            // Add active class to hovered item
            e.target.closest('.search-result-item[data-halte-id]').classList.add('active');
        }
    });

    // Auto-fit map to show all markers
    if (haltesData.length > 0) {
        const group = new L.featureGroup(Object.values(markers));
        if (Object.keys(group._layers).length > 0) {
            map.fitBounds(group.getBounds().pad(0.05));
        }
    }

    // Add geolocation control
    if (navigator.geolocation) {
        const locationControl = L.Control.extend({
            options: {
                position: 'topleft'
            },
            
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.innerHTML = '<a href="#" title="Lokasi Saya" style="text-decoration: none;"><i class="fas fa-crosshairs"></i></a>';
                container.style.backgroundColor = 'white';
                container.style.width = '40px';
                container.style.height = '40px';
                container.style.lineHeight = '40px';
                container.style.textAlign = 'center';
                container.style.color = '#333';
                container.style.fontSize = '16px';
                container.style.borderRadius = '4px';
                container.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                
                container.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    container.innerHTML = '<a href="#" style="text-decoration: none;"><i class="fas fa-spinner fa-spin"></i></a>';
                    
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        map.flyTo([lat, lng], 16, {
                            duration: 1.5
                        });
                        
                        // Add user location marker
                        const userIcon = L.divIcon({
                            className: 'user-location-icon',
                            html: '<div style="background-color: #2563eb; width: 20px; height: 20px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 20px rgba(37, 99, 235, 0.6); position: relative;"><div style="position: absolute; top: -4px; left: -4px; width: 28px; height: 28px; border: 3px solid #2563eb; border-radius: 50%; opacity: 0.4; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });
                        
                        // Remove existing user marker if any
                        if (window.userMarker) {
                            map.removeLayer(window.userMarker);
                        }
                        
                        window.userMarker = L.marker([lat, lng], { icon: userIcon })
                            .addTo(map)
                            .bindPopup('<div style="text-align: center; font-weight: 600; color: #2563eb;"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Anda</div>')
                            .openPopup();
                        
                        container.innerHTML = '<a href="#" title="Lokasi Saya" style="text-decoration: none;"><i class="fas fa-crosshairs"></i></a>';
                    }, function(error) {
                        alert('Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif dan berikan izin lokasi.');
                        container.innerHTML = '<a href="#" title="Lokasi Saya" style="text-decoration: none;"><i class="fas fa-crosshairs"></i></a>';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    });
                };
                
                return container;
            }
        });
        
        map.addControl(new locationControl());
    }

    // Fullscreen functionality
    const fullscreenToggle = document.getElementById('fullscreenToggle');
    const mapSection = document.getElementById('mapSection');
    let isFullscreen = false;

    fullscreenToggle.addEventListener('click', function() {
        if (!isFullscreen) {
            // Enter fullscreen
            mapSection.classList.add('fullscreen-map');
            fullscreenToggle.innerHTML = '<i class="fas fa-compress"></i>';
            fullscreenToggle.title = 'Keluar dari Mode Layar Penuh';
            isFullscreen = true;
        } else {
            // Exit fullscreen
            mapSection.classList.remove('fullscreen-map');
            fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i>';
            fullscreenToggle.title = 'Mode Layar Penuh';
            isFullscreen = false;
        }
        
        // Refresh map size after fullscreen toggle
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });

    // ESC key to exit fullscreen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isFullscreen) {
            fullscreenToggle.click();
        }
    });

    // Map controls and interactions
    map.on('zoomend', function() {
        const zoom = map.getZoom();
        // Adjust marker size based on zoom level
        Object.values(markers).forEach(marker => {
            const icon = marker.options.icon;
            let size = 24;
            if (zoom > 15) {
                size = 28;
            } else if (zoom < 10) {
                size = 20;
            }
            
            // Update marker icon size
            const isRented = icon.options.html.includes('#dc2626');
            const color = isRented ? '#dc2626' : '#059669';
            
            const newIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${color}; width: ${size}px; height: ${size}px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(${isRented ? '220, 38, 38' : '5, 150, 105'}, 0.5); position: relative;"><div style="position: absolute; top: -2px; left: -2px; width: ${size + 4}px; height: ${size + 4}px; border: 2px solid ${color}; border-radius: 50%; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>`,
                iconSize: [size, size],
                iconAnchor: [size/2, size/2]
            });
            
            marker.setIcon(newIcon);
        });
    });

    // Remove loading overlay after map loads
    map.whenReady(() => {
        setTimeout(() => {
            const loadingOverlay = document.getElementById('mapLoading');
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                setTimeout(() => {
                    loadingOverlay.remove();
                }, 300);
            }
        }, 1000);
    });

    // Add scale control
    L.control.scale({
        position: 'bottomleft',
        metric: true,
        imperial: false
    }).addTo(map);

    // Smooth popup opening
    map.on('popupopen', function(e) {
        const popup = e.popup;
        const container = popup._container;
        if (container) {
            container.style.opacity = '0';
            container.style.transform = 'scale(0.8)';
            setTimeout(() => {
                container.style.transition = 'all 0.3s ease';
                container.style.opacity = '1';
                container.style.transform = 'scale(1)';
            }, 50);
        }
    });
});
</script>
@endpush