// Import dependencies if needed
// import L from 'leaflet';
// import 'leaflet/dist/leaflet.css';

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is admin - this will be passed from Blade
    const isAdmin = window.isAdmin || false;
    const haltesData = window.haltesData || [];

    // Initialize map centered on Surabaya, East Java with better options
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: true,
        preferCanvas: false,
        zoomAnimation: true,
        fadeAnimation: true,
        markerZoomAnimation: true
    }).setView([-7.2575, 112.7521], 12);

    // Add OpenStreetMap tiles with better styling
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | E-HalteDishub',
        maxZoom: 19,
        tileSize: 256,
        zoomOffset: 0
    }).addTo(map);

    // Create custom marker icons with better styling
    const availableIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #059669; width: 24px; height: 24px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(5, 150, 105, 0.5); position: relative;"><div style="position: absolute; top: -2px; left: -2px; width: 28px; height: 28px; border: 2px solid #059669; border-radius: 50%; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        popupAnchor: [0, -12]
    });

    const rentedIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="background-color: #dc2626; width: 24px; height: 24px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(220, 38, 38, 0.5); position: relative;"><div style="position: absolute; top: -2px; left: -2px; width: 28px; height: 28px; border: 2px solid #dc2626; border-radius: 50%; opacity: 0.3; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        popupAnchor: [0, -12]
    });

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

    // Function to open marker popup properly
    function openMarkerPopup(marker, halte) {
        // First center the map on the marker
        map.setView([halte.latitude, halte.longitude], Math.max(map.getZoom(), 15), {
            animate: true,
            duration: 0.5
        });

        // Wait for map animation to complete, then open popup
        setTimeout(() => {
            // Ensure popup is created and positioned correctly
            marker.openPopup();

            // Additional positioning fix
            setTimeout(() => {
                const popup = marker.getPopup();
                if (popup && popup._container) {
                    popup.update();
                    map.panIntoView(popup._container, {
                        paddingTopLeft: [20, 20],
                        paddingBottomRight: [20, 20]
                    });
                }
            }, 100);
        }, 500);
    }

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

        // Create marker with improved popup options
        const marker = L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 400,
                minWidth: 300,
                className: 'custom-popup',
                closeButton: true,
                autoPan: true,
                autoPanPaddingTopLeft: [50, 50],
                autoPanPaddingBottomRight: [50, 50],
                keepInView: true,
                offset: [0, -12]
            })
            .addTo(map);

        // Custom marker click handler for better popup positioning
        marker.on('click', function(e) {
            // Close any existing popups first
            map.closePopup();

            // Open this marker's popup with proper positioning
            openMarkerPopup(marker, halte);
        });

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
        results.slice(0, 8).forEach(halte => {
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
                    // Use the same popup opening function for consistency
                    openMarkerPopup(halte.marker, halte);

                    // Highlight marker
                    highlightMarker(halteId);

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

    // Map controls and interactions with better popup handling
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
                iconAnchor: [size/2, size/2],
                popupAnchor: [0, -size/2]
            });

            marker.setIcon(newIcon);
        });

        // Update any open popup position after zoom
        setTimeout(() => {
            const popup = map._popup;
            if (popup && popup._container) {
                popup.update();
            }
        }, 100);
    });

    // Fix popup positioning after map movement
    map.on('moveend', function() {
        const popup = map._popup;
        if (popup && popup._container) {
            setTimeout(() => {
                popup.update();
            }, 50);
        }
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

        // Invalidate size to ensure proper rendering
        setTimeout(() => {
            map.invalidateSize();
        }, 1500);
    });

    // Add scale control
    L.control.scale({
        position: 'bottomleft',
        metric: true,
        imperial: false
    }).addTo(map);

    // Enhanced popup handling
    map.on('popupopen', function(e) {
        const popup = e.popup;
        const container = popup._container;
        if (container) {
            // Initial animation
            container.style.opacity = '0';
            container.style.transform = 'scale(0.8)';

            setTimeout(() => {
                container.style.transition = 'all 0.3s ease';
                container.style.opacity = '1';
                container.style.transform = 'scale(1)';

                // Ensure proper positioning
                popup.update();

                // Pan into view if needed
                const popupLatLng = popup.getLatLng();
                const pixelPoint = map.latLngToContainerPoint(popupLatLng);
                const popupHeight = container.offsetHeight || 400;
                const mapHeight = map.getContainer().offsetHeight;

                // Check if popup is outside viewport
                if (pixelPoint.y < popupHeight + 50) {
                    map.panBy([0, -(popupHeight + 50 - pixelPoint.y)], {
                        animate: true,
                        duration: 0.5
                    });
                }
            }, 50);
        }
    });

    // Handle popup close
    map.on('popupclose', function(e) {
        const popup = e.popup;
        const container = popup._container;
        if (container) {
            container.style.transition = 'all 0.2s ease';
            container.style.opacity = '0';
            container.style.transform = 'scale(0.9)';
        }
    });
});
