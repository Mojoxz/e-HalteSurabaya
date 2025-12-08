import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([-7.4478, 112.7183], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const haltesData = window.haltesData || [];
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

    // Function to show halte info from sidebar
    window.showHalteInfo = function(halte) {
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
    };
});
