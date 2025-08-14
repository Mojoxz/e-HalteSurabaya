@extends('layouts.app')

@section('title', 'Peta Halte Bus')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .info-panel {
        background: white;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.375rem;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    .stat-card.available {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card.rented {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        z-index: 1000;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .legend-available { background-color: #28a745; }
    .legend-rented { background-color: #dc3545; }

    /* Custom popup styles */
    .leaflet-popup-content {
        width: 350px !important;
        margin: 0 !important;
    }
    .leaflet-popup-content-wrapper {
        padding: 0 !important;
    }

    /* Photo carousel styles */
    .popup-photo-container {
        position: relative;
        width: 100%;
        height: 200px;
        margin-bottom: 15px;
        border-radius: 8px 8px 0 0;
        overflow: hidden;
        background: #f8f9fa;
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
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .popup-photo-nav:hover {
        background: rgba(0,0,0,0.7);
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
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
    }
    .no-photos {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-style: italic;
    }

    .popup-content {
        padding: 0;
    }
    .popup-info {
        padding: 15px;
    }
    .popup-title {
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }
    .popup-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.8em;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .status-available {
        background-color: #d4edda;
        color: #155724;
    }
    .status-rented {
        background-color: #f8d7da;
        color: #721c24;
    }
    .popup-details {
        font-size: 0.9em;
        line-height: 1.4;
    }
    .info-row {
        margin-bottom: 5px;
    }
    .info-label {
        font-weight: bold;
        color: #666;
    }
    /* NEW: Detail button styles */
    .popup-actions {
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        text-align: center;
    }
    .btn-detail {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }
    .btn-detail:hover {
        transform: translateY(-1px);
        color: white;
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <h3>{{ $statistics['total'] }}</h3>
                <p class="mb-0"><i class="fas fa-bus"></i> Total Halte</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card available">
                <h3>{{ $statistics['available'] }}</h3>
                <p class="mb-0"><i class="fas fa-check-circle"></i> Tersedia</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card rented">
                <h3>{{ $statistics['rented'] }}</h3>
                <p class="mb-0"><i class="fas fa-clock"></i> Disewa</p>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="info-panel">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="fas fa-info-circle"></i> Informasi Halte Bus</h4>
                <p class="mb-2">Sistem ini menampilkan lokasi halte bus beserta status ketersediaannya. Klik pada marker di peta untuk melihat detail informasi halte.</p>
                <ul class="mb-0">
                    <li><strong>Hijau:</strong> Halte tersedia untuk disewa</li>
                    <li><strong>Merah:</strong> Halte sedang disewa</li>
                    <li>Semua halte telah terdaftar di sistem SIMBADA</li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="text-end">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i> Login Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt"></i> Peta Lokasi Halte Bus
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="map"></div>
                    <div class="map-legend">
                        <h6><strong>Keterangan:</strong></h6>
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
$(document).ready(function() {
    // Initialize map centered on Sidoarjo, East Java
    const map = L.map('map').setView([-7.4478, 112.7183], 11);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Halte data from Laravel
    const haltesData = @json($haltesData);

    // Create marker icons
    const availableIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #28a745; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const rentedIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #dc3545; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    // Function to create photo carousel HTML
    function createPhotoCarousel(photos, halteName) {
        if (!photos || photos.length === 0) {
            return `
                <div class="popup-photo-container">
                    <div class="no-photos">
                        <i class="fas fa-image" style="font-size: 2em; color: #ccc;"></i>
                        <span style="margin-left: 10px;">Tidak ada foto</span>
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
                     onerror="this.style.display='none'">
            `;
        });

        // Add navigation buttons if more than one photo
        if (photos.length > 1) {
            carouselHtml += `
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

    // Add markers for each halte
    haltesData.forEach(function(halte) {
        const icon = halte.rental_status === 'rented' ? rentedIcon : availableIcon;

        // Create photo carousel
        const photoCarousel = createPhotoCarousel(halte.photos, halte.name);

        // Create popup content with carousel and detail button
        let popupContent = `
            <div class="popup-content">
                ${photoCarousel}
                <div class="popup-info">
                    <div class="popup-title">${halte.name}</div>
                    <div class="popup-status ${halte.rental_status === 'rented' ? 'status-rented' : 'status-available'}">
                        ${halte.rental_status === 'rented' ? 'DISEWA' : 'TERSEDIA'}
                    </div>
                    <div class="popup-details">
                        ${halte.description ? `<div class="info-row"><span class="info-label">Deskripsi:</span> ${halte.description}</div>` : ''}
                        ${halte.address ? `<div class="info-row"><span class="info-label">Alamat:</span> ${halte.address}</div>` : ''}
                        ${halte.is_rented && halte.rented_by ? `<div class="info-row"><span class="info-label">Disewa oleh:</span> ${halte.rented_by}</div>` : ''}
                        ${halte.is_rented && halte.rent_end_date ? `<div class="info-row"><span class="info-label">Sewa sampai:</span> ${halte.rent_end_date}</div>` : ''}
                        ${halte.simbada_registered ? `<div class="info-row"><span class="info-label">SIMBADA:</span> <span class="badge bg-success" style="background-color: #28a745!important; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75em;">Terdaftar</span></div>` : ''}
                        ${halte.simbada_number ? `<div class="info-row"><span class="info-label">No. SIMBADA:</span> ${halte.simbada_number}</div>` : ''}
                        <div class="info-row"><span class="info-label">Koordinat:</span> ${halte.latitude}, ${halte.longitude}</div>
                    </div>
                </div>
                <div class="popup-actions">
                    <a href="/halte/${halte.id}/detail" class="btn-detail">
                        <i class="fas fa-info-circle"></i> Lihat Detail Lengkap
                    </a>
                </div>
            </div>
        `;

        // Add marker to map
        L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 370,
                className: 'custom-popup',
                closeButton: true
            })
            .addTo(map);
    });

    // Auto-fit map to show all markers
    if (haltesData.length > 0) {
        const group = new L.featureGroup(map._layers);
        if (Object.keys(group._layers).length > 0) {
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }
});
</script>
@endpush
