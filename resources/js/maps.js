// resources/js/maps.js - FIXED POPUP ISSUES
// Ganti SELURUH file maps.js dengan kode ini

document.addEventListener('DOMContentLoaded', function() {
    const isAdmin = window.isAdmin || false;
    const haltesData = window.haltesData || [];

    // Initialize map
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: true,
        preferCanvas: false,
        zoomAnimation: true,
        fadeAnimation: true,
        markerZoomAnimation: true
    }).setView([-7.2575, 112.7521], 12);

    // Add tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | E-HalteDishub',
        maxZoom: 19,
        tileSize: 256,
        zoomOffset: 0
    }).addTo(map);

    // Custom marker icons
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

    const markers = {};
    const searchData = [];

    // Photo carousel function
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

    // Change photo function
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

    // ✅ FIXED: Handle detail click
    window.handleDetailClick = function(halteId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (isAdmin) {
            window.location.href = `/halte/${halteId}/detail`;
        } else {
            // Close Leaflet popup first
            map.closePopup();

            setTimeout(() => {
                const modalElement = document.getElementById('accessRestrictedModal');
                if (modalElement) {
                    modalElement.removeAttribute('aria-hidden');

                    const bsModal = new bootstrap.Modal(modalElement, {
                        backdrop: 'static',
                        keyboard: true,
                        focus: true
                    });

                    bsModal.show();

                    modalElement.addEventListener('shown.bs.modal', function() {
                        this.removeAttribute('aria-hidden');
                        const focusableElement = this.querySelector('.btn-close, button, a, input');
                        if (focusableElement) {
                            focusableElement.focus();
                        }
                    }, { once: true });
                }
            }, 100);
        }
    };

    // ✅ FIXED: Improved popup opening function
    function openMarkerPopup(marker, halte) {
        // Close any existing popups
        map.closePopup();

        // Calculate zoom level
        const currentZoom = map.getZoom();
        const targetZoom = Math.max(currentZoom, 15);

        // Center map on marker
        map.setView([halte.latitude, halte.longitude], targetZoom, {
            animate: true,
            duration: 0.5,
            easeLinearity: 0.25
        });

        // Wait for map to settle before opening popup
        setTimeout(() => {
            // Force marker to open popup
            marker.openPopup();

            // Additional adjustment after popup opens
            setTimeout(() => {
                const popup = marker.getPopup();
                if (popup && popup._container && popup.isOpen()) {
                    popup.update();

                    // Pan if needed
                    const popupLatLng = popup.getLatLng();
                    const pixelPoint = map.latLngToContainerPoint(popupLatLng);
                    const popupHeight = popup._container.offsetHeight || 400;

                    if (pixelPoint.y < popupHeight + 50) {
                        map.panBy([0, -(popupHeight + 50 - pixelPoint.y)], {
                            animate: true,
                            duration: 0.3
                        });
                    }
                }
            }, 150);
        }, 600);
    }

    // Add markers
    haltesData.forEach(function(halte) {
        const icon = halte.rental_status === 'rented' ? rentedIcon : availableIcon;
        const photoCarousel = createPhotoCarousel(halte.photos, halte.name);

        const detailButton = isAdmin
            ? `<a href="/halte/${halte.id}/detail" class="btn-detail">
                <i class="fas fa-info-circle me-1"></i> Lihat Detail Lengkap
               </a>`
            : `<button type="button" onclick="handleDetailClick(${halte.id}, event)" class="btn-detail">
                <i class="fas fa-info-circle me-1"></i> Lihat Detail Lengkap
               </button>`;

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

        // ✅ FIXED: Create marker with better event handling
        const marker = L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 400,
                minWidth: 300,
                className: 'custom-popup',
                closeButton: true,
                autoClose: true,
                closeOnClick: false,
                autoPan: true,
                autoPanPaddingTopLeft: [50, 50],
                autoPanPaddingBottomRight: [50, 50],
                keepInView: true,
                offset: [0, -12]
            })
            .addTo(map);

        // ✅ FIXED: Better click handler
        marker.on('click', function(e) {
            L.DomEvent.stopPropagation(e);
            openMarkerPopup(marker, halte);
        });

        markers[halte.id] = marker;

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

    function normalizeText(text) {
        return text.toLowerCase()
            .replace(/[àáâãäå]/g, 'a')
            .replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i')
            .replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u')
            .trim();
    }

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

        searchResults.querySelectorAll('.search-result-item[data-halte-id]').forEach(item => {
            item.addEventListener('click', function() {
                const halteId = this.dataset.halteId;
                const halte = searchData.find(h => h.id == halteId);

                if (halte) {
                    openMarkerPopup(halte.marker, halte);
                    highlightMarker(halteId);
                    searchResults.style.display = 'none';
                    searchInput.value = halte.name;
                    clearButton.style.display = 'block';
                }
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length === 0) {
                searchResults.style.display = 'none';
                clearButton.style.display = 'none';
                return;
            }

            clearButton.style.display = 'block';
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                performSearch(this.value.trim());
            }
        });

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
    }

    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            searchResults.style.display = 'none';
            clearButton.style.display = 'none';
            searchInput.focus();
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-container')) {
            if (searchResults) {
                searchResults.style.display = 'none';
            }
        }
    });

    if (searchResults) {
        searchResults.addEventListener('mouseover', function(e) {
            if (e.target.closest('.search-result-item[data-halte-id]')) {
                this.querySelectorAll('.search-result-item.active').forEach(item => {
                    item.classList.remove('active');
                });
                e.target.closest('.search-result-item[data-halte-id]').classList.add('active');
            }
        });
    }

    // Auto-fit map
    if (haltesData.length > 0) {
        const group = new L.featureGroup(Object.values(markers));
        if (Object.keys(group._layers).length > 0) {
            map.fitBounds(group.getBounds().pad(0.05));
        }
    }

    // Geolocation control
    if (navigator.geolocation) {
        const locationControl = L.Control.extend({
            options: { position: 'topleft' },
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.innerHTML = '<a href="#" title="Lokasi Saya" style="text-decoration: none;"><i class="fas fa-crosshairs"></i></a>';
                container.style.cssText = 'background: white; width: 40px; height: 40px; line-height: 40px; text-align: center; color: #333; font-size: 16px; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);';

                container.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    container.innerHTML = '<a href="#" style="text-decoration: none;"><i class="fas fa-spinner fa-spin"></i></a>';

                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        map.flyTo([lat, lng], 16, { duration: 1.5 });

                        const userIcon = L.divIcon({
                            className: 'user-location-icon',
                            html: '<div style="background-color: #2563eb; width: 20px; height: 20px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 20px rgba(37, 99, 235, 0.6); position: relative;"><div style="position: absolute; top: -4px; left: -4px; width: 28px; height: 28px; border: 3px solid #2563eb; border-radius: 50%; opacity: 0.4; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div></div>',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        if (window.userMarker) {
                            map.removeLayer(window.userMarker);
                        }

                        window.userMarker = L.marker([lat, lng], { icon: userIcon })
                            .addTo(map)
                            .bindPopup('<div style="text-align: center; font-weight: 600; color: #2563eb;"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Anda</div>')
                            .openPopup();

                        container.innerHTML = '<a href="#" title="Lokasi Saya" style="text-decoration: none;"><i class="fas fa-crosshairs"></i></a>';
                    }, function(error) {
                        alert('Tidak dapat mengakses lokasi Anda.');
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

    // Fullscreen
    const fullscreenToggle = document.getElementById('fullscreenToggle');
    const mapSection = document.getElementById('mapSection');
    let isFullscreen = false;

    if (fullscreenToggle && mapSection) {
        fullscreenToggle.addEventListener('click', function() {
            if (!isFullscreen) {
                mapSection.classList.add('fullscreen-map');
                fullscreenToggle.innerHTML = '<i class="fas fa-compress"></i>';
                fullscreenToggle.title = 'Keluar dari Mode Layar Penuh';
                isFullscreen = true;
            } else {
                mapSection.classList.remove('fullscreen-map');
                fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i>';
                fullscreenToggle.title = 'Mode Layar Penuh';
                isFullscreen = false;
            }

            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isFullscreen && fullscreenToggle) {
            fullscreenToggle.click();
        }
    });

    // ✅ FIXED: Better popup event handling
    map.on('popupopen', function(e) {
        const popup = e.popup;
        const container = popup._container;
        if (container) {
            container.style.opacity = '0';
            container.style.transform = 'scale(0.9)';

            setTimeout(() => {
                container.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                container.style.opacity = '1';
                container.style.transform = 'scale(1)';
                popup.update();
            }, 50);
        }
    });

    map.on('popupclose', function(e) {
        const popup = e.popup;
        const container = popup._container;
        if (container) {
            container.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            container.style.opacity = '0';
            container.style.transform = 'scale(0.95)';
        }
    });

    // Remove loading
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

        setTimeout(() => {
            map.invalidateSize();
        }, 1500);
    });

    // Scale control
    L.control.scale({
        position: 'bottomleft',
        metric: true,
        imperial: false
    }).addTo(map);
});
