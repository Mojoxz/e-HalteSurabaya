// Home page JavaScript functionality
// Variables will be passed from Blade template: isAdmin, haltesData

// Wait for DOM and all scripts to load
window.addEventListener('load', function() {
    // Check if required libraries are loaded
    if (typeof L === 'undefined') {
        console.error('Leaflet is not loaded');
        return;
    }
    if (typeof AOS === 'undefined') {
        console.error('AOS is not loaded');
        return;
    }
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded');
        return;
    }

    console.log('All libraries loaded, initializing home.js...');

    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    // Hero Slider Functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const navDots = document.querySelectorAll('.hero-nav-dot');
    const totalSlides = slides.length;

    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            navDots[i].classList.remove('active');
        });

        // Show current slide
        slides[index].classList.add('active');
        navDots[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    // Auto-advance slider every 5 seconds
    setInterval(nextSlide, 5000);

    // Click handlers for navigation dots
    navDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Check if haltesData is available
    if (typeof window.haltesData === 'undefined') {
        console.error('haltesData is not defined');
        return;
    }

    // Initialize Leaflet map centered on Surabaya, East Java
    const map = L.map('map').setView([-7.2575, 112.7521], 12);

    // Add OpenStreetMap tiles with better styling
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
        tileSize: 256,
        zoomOffset: 0
    }).addTo(map);

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

    // Store markers and halte data for search functionality
    const markers = {};
    const searchData = [];

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

    // Function to handle detail button click with SweetAlert2
    window.handleDetailClick = function(halteId, event) {
        event.preventDefault();

        if (window.isAdmin) {
            // Admin can access detail page directly
            window.location.href = `/halte/${halteId}/detail`;
        } else {
            // Show SweetAlert2 for non-admin users
            Swal.fire({
                icon: 'warning',
                title: 'Akses Terbatas',
                html: `
                    <div style="text-align: center; padding: 20px 0;">
                        <i class="fas fa-lock" style="font-size: 3em; color: #f0ad4e; margin-bottom: 20px;"></i>
                        <p style="font-size: 1.1em; margin-bottom: 15px;">
                            Detail lengkap halte hanya dapat diakses oleh User yang memiliki Akun.
                        </p>
                        <p style="color: #666;">
                            Silakan hubungi admin untuk meminta akun.
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Login ',
                cancelButtonText: '<i class="fas fa-check"></i> Saya Mengerti',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to login page
                    window.location.href = '/login';
                }
            });
        }
    };

    // Add markers for each halte
    window.haltesData.forEach(function(halte) {
        const icon = halte.rental_status === 'rented' ? rentedIcon : availableIcon;

        // Create photo carousel
        const photoCarousel = createPhotoCarousel(halte.photos, halte.name);

        // Create detail button with conditional behavior
        const detailButton = window.isAdmin
            ? `<a href="/halte/${halte.id}/detail" class="btn-detail">
                <i class="fas fa-info-circle"></i> Lihat Detail Lengkap
               </a>`
            : `<button onclick="handleDetailClick(${halte.id}, event)" class="btn-detail">
                <i class="fas fa-info-circle"></i> Lihat Detail Lengkap
               </button>`;

        // Create popup content with carousel and conditional detail button
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
                    ${detailButton}
                </div>
            </div>
        `;

        // Create marker
        const marker = L.marker([halte.latitude, halte.longitude], { icon: icon })
            .bindPopup(popupContent, {
                maxWidth: 370,
                className: 'custom-popup',
                closeButton: true
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
            searchResults.innerHTML = '<div class="no-results">Tidak ada halte yang ditemukan</div>';
            searchResults.style.display = 'block';
            return;
        }

        let resultsHtml = '';
        results.slice(0, 5).forEach(halte => {
            resultsHtml += `
                <div class="search-result-item" data-halte-id="${halte.id}">
                    <div class="search-result-name">${halte.name}</div>
                    <div class="search-result-info">
                        ${halte.address ? halte.address + ' • ' : ''}
                        <span style="color: ${halte.status === 'Disewa' ? '#dc3545' : '#28a745'}">
                            ${halte.status}
                        </span>
                    </div>
                </div>
            `;
        });

        if (results.length > 5) {
            resultsHtml += `<div class="search-result-item" style="font-style: italic; color: #999;">...dan ${results.length - 5} hasil lainnya</div>`;
        }

        searchResults.innerHTML = resultsHtml;
        searchResults.style.display = 'block';

        // Add click handlers to search results
        searchResults.querySelectorAll('.search-result-item[data-halte-id]').forEach(item => {
            item.addEventListener('click', function() {
                const halteId = this.dataset.halteId;
                const halte = searchData.find(h => h.id == halteId);

                if (halte) {
                    map.setView([halte.latitude, halte.longitude], 16);
                    highlightMarker(halteId);
                    setTimeout(() => {
                        halte.marker.openPopup();
                    }, 1000);
                    searchResults.style.display = 'none';
                    searchInput.value = halte.name;
                    clearButton.style.display = 'block';
                }
            });
        });
    }

    // Search input event listeners
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

    // Clear search functionality
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            searchResults.style.display = 'none';
            clearButton.style.display = 'none';
            searchInput.focus();
        });
    }

    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.map-search-container')) {
            searchResults.style.display = 'none';
        }
    });

    // Add hover effect for keyboard navigation
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

    // Auto-fit map to show all markers
    if (window.haltesData.length > 0) {
        const group = new L.featureGroup(Object.values(markers));
        if (Object.keys(group._layers).length > 0) {
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add parallax effect to hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.hero-section');
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });

    // Counter animation for statistics
    function animateCounter(element, target, duration = 2000) {
        let current = 0;
        const increment = target / (duration / 16);

        function updateCounter() {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        }

        updateCounter();
    }

    // Animate counters when they come into view
    const observerOptions = {
        threshold: 0.7
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target.querySelector('.hero-stat-number');
                if (counter) {
                    const target = parseInt(counter.textContent);
                    counter.textContent = '0';
                    animateCounter(counter, target);
                    counterObserver.unobserve(entry.target);
                }
            }
        });
    }, observerOptions);

    document.querySelectorAll('.hero-stat').forEach(stat => {
        counterObserver.observe(stat);
    });

    // Add loading spinner for map
    const mapContainer = document.getElementById('map');
    if (mapContainer) {
        mapContainer.style.position = 'relative';

        const loadingOverlay = document.createElement('div');
        loadingOverlay.innerHTML = `
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255,255,255,0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                border-radius: 20px;
            ">
                <div style="text-align: center;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="margin-top: 10px; color: #666;">Memuat peta dan data halte...</p>
                </div>
            </div>
        `;

        mapContainer.appendChild(loadingOverlay);

        map.whenReady(() => {
            setTimeout(() => {
                if (loadingOverlay && loadingOverlay.parentNode) {
                    loadingOverlay.remove();
                }
            }, 1500);
        });
    }

    // Add map controls for better user experience
    map.on('zoomend', function() {
        const zoom = map.getZoom();
        Object.values(markers).forEach(marker => {
            const icon = marker.options.icon;
            let size = 20;
            if (zoom > 15) {
                size = 25;
            } else if (zoom < 10) {
                size = 15;
            }

            const isRented = icon.options.html.includes('#dc3545');
            const color = isRented ? '#dc3545' : '#28a745';

            const newIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${color}; width: ${size}px; height: ${size}px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>`,
                iconSize: [size, size],
                iconAnchor: [size/2, size/2]
            });

            marker.setIcon(newIcon);
        });
    });

    // Add geolocation control
    if (navigator.geolocation) {
        const locationControl = L.Control.extend({
            options: {
                position: 'topleft'
            },

            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.innerHTML = '<a href="#" title="Lokasi Saya"><i class="fas fa-crosshairs"></i></a>';
                container.style.backgroundColor = 'white';
                container.style.width = '30px';
                container.style.height = '30px';
                container.style.lineHeight = '30px';
                container.style.textAlign = 'center';
                container.style.textDecoration = 'none';
                container.style.color = '#333';

                container.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    container.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        map.setView([lat, lng], 16);

                        const userIcon = L.divIcon({
                            className: 'user-location-icon',
                            html: '<div style="background-color: #007bff; width: 15px; height: 15px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(0,123,255,0.5);"></div>',
                            iconSize: [15, 15],
                            iconAnchor: [7.5, 7.5]
                        });

                        const userMarker = L.marker([lat, lng], { icon: userIcon })
                            .addTo(map)
                            .bindPopup('Lokasi Anda')
                            .openPopup();

                        setTimeout(() => {
                            map.removeLayer(userMarker);
                        }, 10000);

                        container.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    }, function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lokasi Tidak Tersedia',
                            text: 'Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi telah diaktifkan.',
                            confirmButtonColor: '#4CAF50'
                        });
                        container.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    });
                };

                return container;
            }
        });

        map.addControl(new locationControl());
    }

    console.log('Home.js initialization complete!');
});
