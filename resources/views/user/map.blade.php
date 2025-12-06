@extends('layouts.user')

@section('title', 'Peta Halte')
@section('page-title', 'Peta Halte')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Map Container Card */
    .map-container-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .map-container-card .card-header {
        background: linear-gradient(135deg, var(--accent-dark) 0%, var(--secondary-dark) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px 24px;
    }

    .map-container-card .card-header h5 {
        color: var(--text-primary);
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .map-container-card .card-body {
        padding: 0;
        position: relative;
    }

    /* Search Box Integrated in Map */
    .map-search-container {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        width: 350px;
    }

    .map-search-box {
        background: rgba(45, 49, 57, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .map-search-input-group {
        position: relative;
        margin-bottom: 0;
    }

    .map-search-input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        background: rgba(61, 66, 77, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .map-search-input:focus {
        outline: none;
        border-color: var(--accent-color);
        background: rgba(61, 66, 77, 1);
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
    }

    .map-search-input::placeholder {
        color: var(--text-secondary);
    }

    .map-search-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        pointer-events: none;
    }

    .map-search-results {
        margin-top: 8px;
        max-height: 300px;
        overflow-y: auto;
        background: rgba(45, 49, 57, 0.98);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        display: none;
    }

    .map-search-results.show {
        display: block;
    }

    .map-search-results::-webkit-scrollbar {
        width: 6px;
    }

    .map-search-results::-webkit-scrollbar-track {
        background: transparent;
    }

    .map-search-results::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .search-result-item {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:hover {
        background: rgba(108, 99, 255, 0.1);
    }

    .search-result-title {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .search-result-address {
        color: var(--text-secondary);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .search-result-status {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        margin-top: 4px;
    }

    .search-status-available {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .search-status-rented {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .search-no-results {
        padding: 20px;
        text-align: center;
        color: var(--text-secondary);
    }

    /* Statistics Cards */
    .stats-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .stats-card.success {
        border-left: 4px solid #10b981;
    }

    .stats-card.warning {
        border-left: 4px solid #f59e0b;
    }

    .stats-card h3 {
        color: var(--text-primary);
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stats-card p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 14px;
    }

    /* Halte List Sidebar */
    .halte-list-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .halte-list-card .card-header {
        background: linear-gradient(135deg, var(--accent-dark) 0%, var(--secondary-dark) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px 24px;
    }

    .halte-list-card .card-header h5 {
        color: var(--text-primary);
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .halte-list-card .card-body {
        padding: 0;
        max-height: 700px;
        overflow-y: auto;
        background: var(--primary-dark);
    }

    .halte-list-card .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .halte-list-card .card-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .halte-list-card .card-body::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .halte-card {
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        background: var(--secondary-dark);
    }

    .halte-card:hover {
        background: var(--accent-dark);
        transform: translateX(4px);
    }

    .halte-card.status-available {
        border-left: 4px solid #10b981;
    }

    .halte-card.status-rented {
        border-left: 4px solid #f59e0b;
    }

    .halte-card h6 {
        color: var(--text-primary);
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .halte-card .text-muted {
        color: var(--text-secondary) !important;
        font-size: 13px;
    }

    .halte-card .badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 10px;
    }

    .halte-card .badge.bg-success {
        background: rgba(16, 185, 129, 0.2) !important;
        color: #10b981;
    }

    .halte-card .badge.bg-warning {
        background: rgba(245, 158, 11, 0.2) !important;
        color: #f59e0b;
    }

    .halte-card img {
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .halte-card .bg-light {
        background: var(--accent-dark) !important;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    /* Map Legend */
    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(45, 49, 57, 0.95);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 1000;
    }

    .map-legend h6 {
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 13px;
    }

    .legend-item:last-child {
        margin-bottom: 0;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .legend-available {
        background-color: #10b981;
    }

    .legend-rented {
        background-color: #ef4444;
    }

    /* Custom popup styles */
    .leaflet-popup-content {
        width: 350px !important;
        margin: 0 !important;
    }

    .leaflet-popup-content-wrapper {
        padding: 0 !important;
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
    }

    .leaflet-popup-tip {
        background: var(--secondary-dark);
    }

    /* Photo carousel styles */
    .popup-photo-container {
        position: relative;
        width: 100%;
        height: 200px;
        margin-bottom: 0;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        background: var(--primary-dark);
    }

    .popup-photo {
        width: 100%;
        height: 200px;
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
        background: rgba(45, 49, 57, 0.9);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        transition: all 0.2s ease;
    }

    .popup-photo-nav:hover {
        background: rgba(108, 99, 255, 0.9);
        border-color: var(--accent-color);
    }

    .popup-photo-nav.prev {
        left: 10px;
    }

    .popup-photo-nav.next {
        right: 10px;
    }

    .popup-photo-counter {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(45, 49, 57, 0.9);
        color: white;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .no-photos {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-style: italic;
    }

    .popup-content {
        padding: 0;
    }

    .popup-info {
        padding: 16px;
        background: var(--secondary-dark);
    }

    .popup-title {
        font-size: 1.1em;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .popup-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 0.75em;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .status-available-popup {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .status-rented-popup {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .popup-details {
        font-size: 0.9em;
        line-height: 1.6;
    }

    .info-row {
        margin-bottom: 6px;
        color: var(--text-secondary);
    }

    .info-label {
        font-weight: 600;
        color: var(--text-primary);
    }

    .popup-actions {
        padding: 12px 16px;
        background: var(--primary-dark);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .btn-detail {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--hover-color) 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 4px 12px rgba(108, 99, 255, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3em;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h6 {
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .map-search-container {
            width: calc(100% - 40px);
            left: 20px;
            right: 20px;
        }

        .map-legend {
            bottom: 10px;
            right: 10px;
            padding: 12px;
        }

        #map {
            height: 400px;
        }

        .halte-list-card .card-body {
            max-height: 400px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Map Section -->
        <div class="col-lg-8">
            <div class="map-container-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-map-marked-alt"></i> Peta Lokasi Halte
                        <span class="badge bg-primary ms-2">{{ count($haltesData) }} Halte</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Box inside Map -->
                    <div class="map-search-container">
                        <div class="map-search-box">
                            <div class="map-search-input-group">
                                <input type="text"
                                       id="mapSearchInput"
                                       class="map-search-input"
                                       placeholder="Cari nama halte atau alamat..."
                                       autocomplete="off">
                                <i class="fas fa-search map-search-icon"></i>
                            </div>
                            <div class="map-search-results" id="mapSearchResults"></div>
                        </div>
                    </div>

                    <div id="map"></div>

                    <div class="map-legend">
                        <h6>Keterangan:</h6>
                        <div class="legend-item">
                            <div class="legend-color legend-available"></div>
                            <span>Tersedia</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-rented"></div>
                            <span>Disewa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="stats-card success">
                        <h3>{{ $haltesData->where('rental_status', 'available')->count() }}</h3>
                        <p><i class="fas fa-check-circle"></i> Halte Tersedia</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card warning">
                        <h3>{{ $haltesData->where('rental_status', 'rented')->count() }}</h3>
                        <p><i class="fas fa-calendar"></i> Halte Disewa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Halte List Sidebar -->
        <div class="col-lg-4">
            <div class="halte-list-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list"></i> Daftar Halte
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($haltesData as $halte)
                    <div class="halte-card {{ $halte['rental_status'] === 'available' ? 'status-available' : 'status-rented' }}"
                         onclick="showHalteInfo({{ json_encode($halte) }})">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6>{{ $halte['name'] }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt"></i> {{ Str::limit($halte['address'], 40) }}
                                </p>
                                <span class="badge {{ $halte['rental_status'] === 'available' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $halte['rental_status'] === 'available' ? 'Tersedia' : 'Disewa' }}
                                </span>
                            </div>
                            <div class="text-end">
                                @if($halte['primary_photo'])
                                <img src="{{ $halte['primary_photo'] }}"
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="{{ $halte['name'] }}">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if(count($haltesData) === 0)
                    <div class="empty-state">
                        <i class="fas fa-bus"></i>
                        <h6>Tidak ada halte tersedia</h6>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([-7.4478, 112.7183], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const haltesData = @json($haltesData);
    const markers = {};

    const availableIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #10b981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const rentedIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    function createPhotoCarousel(photos, halteName) {
        if (!photos || photos.length === 0) {
            return `
                <div class="popup-photo-container">
                    <div class="no-photos">
                        <i class="fas fa-image" style="font-size: 2em;"></i>
                        <span style="margin-left: 10px;">Tidak ada foto</span>
                    </div>
                </div>
            `;
        }

        let html = '<div class="popup-photo-container">';
        photos.forEach((photo, index) => {
            html += `<img src="${photo}" alt="${halteName}" class="popup-photo ${index === 0 ? 'active' : ''}" data-index="${index}">`;
        });

        if (photos.length > 1) {
            html += `
                <button class="popup-photo-nav prev" onclick="changePhoto(this, -1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="popup-photo-nav next" onclick="changePhoto(this, 1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="popup-photo-counter">
                    <span class="current-photo">1</span>/<span class="total-photos">${photos.length}</span>
                </div>
            `;
        }

        html += '</div>';
        return html;
    }

    window.changePhoto = function(button, direction) {
        const container = button.closest('.popup-photo-container');
        const photos = container.querySelectorAll('.popup-photo');
        const counter = container.querySelector('.current-photo');

        let currentIndex = -1;
        photos.forEach((photo, index) => {
            if (photo.classList.contains('active')) currentIndex = index;
            photo.classList.remove('active');
        });

        let newIndex = currentIndex + direction;
        if (newIndex >= photos.length) newIndex = 0;
        if (newIndex < 0) newIndex = photos.length - 1;

        photos[newIndex].classList.add('active');
        if (counter) counter.textContent = newIndex + 1;
    };

    haltesData.forEach(function(halte) {
        const icon = halte.rental_status === 'rented' ? rentedIcon : availableIcon;
        const photoCarousel = createPhotoCarousel(halte.photos, halte.name);

        const popupContent = `
            <div class="popup-content">
                ${photoCarousel}
                <div class="popup-info">
                    <div class="popup-title">${halte.name}</div>
                    <div class="popup-status ${halte.rental_status === 'rented' ? 'status-rented-popup' : 'status-available-popup'}">
                        ${halte.rental_status === 'rented' ? 'DISEWA' : 'TERSEDIA'}
                    </div>
                    <div class="popup-details">
                        ${halte.description ? `<div class="info-row"><span class="info-label">Deskripsi:</span> ${halte.description}</div>` : ''}
                        ${halte.address ? `<div class="info-row"><span class="info-label">Alamat:</span> ${halte.address}</div>` : ''}
                        ${halte.is_rented && halte.rented_by ? `<div class="info-row"><span class="info-label">Disewa oleh:</span> ${halte.rented_by}</div>` : ''}
                        ${halte.is_rented && halte.rent_end_date ? `<div class="info-row"><span class="info-label">Sewa sampai:</span> ${halte.rent_end_date}</div>` : ''}
                        ${halte.simbada_registered ? `<div class="info-row"><span class="info-label">SIMBADA:</span> <span style="background-color: rgba(16, 185, 129, 0.2); color: #10b981; padding: 2px 8px; border-radius: 6px; font-size: 0.75em; font-weight: 600;">Terdaftar</span></div>` : ''}
                        ${halte.simbada_number ? `<div class="info-row"><span class="info-label">No. SIMBADA:</span> ${halte.simbada_number}</div>` : ''}
                        <div class="info-row"><span class="info-label">Koordinat:</span> ${halte.latitude}, ${halte.longitude}</div>
                    </div>
                </div>
                <div class="popup-actions">
                    <a href="/user/haltes/${halte.id}" class="btn-detail">
                        <i class="fas fa-info-circle"></i> Lihat Detail Lengkap
                    </a>
                </div>
            </div>
        `;

        const marker = L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 370,
                className: 'custom-popup',
                closeButton: true
            })
            .addTo(map);

        markers[halte.id] = { marker: marker, data: halte };
    });

    if (haltesData.length > 0) {
        const bounds = L.latLngBounds(haltesData.map(h => [h.latitude, h.longitude]));
        map.fitBounds(bounds.pad(0.1));
    }

    // Search functionality
    const searchInput = document.getElementById('mapSearchInput');
    const searchResults = document.getElementById('mapSearchResults');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();

        if (query === '') {
            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
            return;
        }

        const filtered = haltesData.filter(halte => {
            return halte.name.toLowerCase().includes(query) ||
                   (halte.address && halte.address.toLowerCase().includes(query));
        });

        if (filtered.length > 0) {
            searchResults.innerHTML = filtered.map(halte => `
                <div class="search-result-item" data-id="${halte.id}">
                    <div class="search-result-title">${halte.name}</div>
                    <div class="search-result-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${halte.address || 'Alamat tidak tersedia'}</span>
                    </div>
                    <span class="search-result-status ${halte.rental_status === 'available' ? 'search-status-available' : 'search-status-rented'}">
                        ${halte.rental_status === 'available' ? 'TERSEDIA' : 'DISEWA'}
                    </span>
                </div>
            `).join('');
            searchResults.classList.add('show');
        } else {
            searchResults.innerHTML = '<div class="search-no-results"><i class="fas fa-search"></i><p>Tidak ada hasil ditemukan</p></div>';
            searchResults.classList.add('show');
        }
    });

    // Handle search result click
    searchResults.addEventListener('click', function(e) {
        const resultItem = e.target.closest('.search-result-item');
        if (resultItem) {
            const halteId = resultItem.dataset.id;
            const markerData = markers[halteId];

            if (markerData) {
                map.setView([markerData.data.latitude, markerData.data.longitude], 16);
                markerData.marker.openPopup();
                searchInput.value = '';
                searchResults.classList.remove('show');
                searchResults.innerHTML = '';
            }
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-container')) {
            searchResults.classList.remove('show');
        }
    });

    // Prevent search box from closing when clicking inside
    document.querySelector('.map-search-container').addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

// Function to show halte info from sidebar
function showHalteInfo(halte) {
    map.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
            const lat = layer.getLatLng().lat;
            const lng = layer.getLatLng().lng;

            if (Math.abs(lat - halte.latitude) < 0.0001 && Math.abs(lng - halte.longitude) < 0.0001) {
                map.setView([lat, lng], 15);
                layer.openPopup();
            }
        }
    });
}
</script>
@endpush
