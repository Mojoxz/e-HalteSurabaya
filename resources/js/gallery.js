// Global variables
let halteData = [];
let currentMainSlide = 0;
let photoSwiper = null;
let isCarouselView = true;
let autoPlayInterval;
let touchStartX = 0;
let touchEndX = 0;

// Initialize gallery - exposed to global scope
window.initGallery = function(haltes) {
    halteData = haltes;

    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100
        });
    }

    // Initialize carousel if there's data
    if (halteData.length > 0) {
        initializeCarousel();
    }

    // Setup event listeners
    setupViewToggle();
    setupFilterButtons();
    setupBackToTop();
    setupLoadMore();
    setupModalListeners();
    setupParallax();
    setupIntersectionObserver();
    setupTouchSupport();
    setupResizeHandler();
    setupImageErrorHandling();

    // Start auto-play
    setTimeout(startAutoPlay, 3000);

    // Preload images
    setTimeout(preloadImages, 1000);

    // Add SweetAlert2 custom styles
    addSweetAlertStyles();

    console.log('Gallery carousel initialized with', halteData.length, 'halte(s)');
}

// Add custom styles for SweetAlert2
function addSweetAlertStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .swal2-custom-popup {
            border-radius: 20px !important;
            font-family: Arial, sans-serif;
        }

        .swal2-custom-confirm {
            border-radius: 30px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3) !important;
        }

        .swal2-custom-cancel {
            border-radius: 30px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #ffc107 !important;
            color: #ffc107 !important;
        }

        .swal2-html-container {
            margin: 1rem 0 !important;
        }
    `;
    document.head.appendChild(style);
}

// View toggle functionality
function setupViewToggle() {
    const carouselViewBtn = document.getElementById('carouselViewBtn');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const carouselContainer = document.getElementById('carouselContainer');
    const traditionalGrid = document.getElementById('traditionalGrid');

    if (!carouselViewBtn || !gridViewBtn) return;

    carouselViewBtn.addEventListener('click', function() {
        if (!isCarouselView) {
            isCarouselView = true;
            this.classList.add('active');
            gridViewBtn.classList.remove('active');
            carouselContainer.style.display = 'block';
            traditionalGrid.style.display = 'none';
        }
    });

    gridViewBtn.addEventListener('click', function() {
        if (isCarouselView) {
            isCarouselView = false;
            this.classList.add('active');
            carouselViewBtn.classList.remove('active');
            carouselContainer.style.display = 'none';
            traditionalGrid.style.display = 'grid';
        }
    });
}

// Enhanced Filter functionality
function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-btn[data-filter]');
    const halteCards = document.querySelectorAll('.halte-card[data-filter]');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Filter halte data for carousel
            let filteredHaltes = halteData;
            if (filter !== 'all') {
                filteredHaltes = halteData.filter(halte => {
                    const isRented = halte.rentals && halte.rentals.some(rental =>
                        new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
                    );
                    const isAvailable = !isRented;

                    switch(filter) {
                        case 'available':
                            return isAvailable;
                        case 'rented':
                            return isRented;
                        case 'simbada':
                            return halte.simbada_registered;
                        default:
                            return true;
                    }
                });
            }

            // Update carousel with filtered data
            updateCarousel(filteredHaltes);

            // Filter traditional grid cards
            halteCards.forEach((card, index) => {
                const cardFilters = card.getAttribute('data-filter').split(' ');

                if (filter === 'all' || cardFilters.includes(filter)) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    }, index * 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px) scale(0.95)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Carousel initialization
function initializeCarousel() {
    updateCarousel(halteData);
}

function updateCarousel(data) {
    if (data.length === 0) {
        const container = document.getElementById('carouselContainer');
        if (container) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bus"></i>
                    <h3>Tidak ada halte yang sesuai filter</h3>
                    <p>Coba ubah filter untuk melihat halte lainnya.</p>
                </div>
            `;
        }
        return;
    }

    // Reset current slide if it exceeds new data length
    if (currentMainSlide >= data.length) {
        currentMainSlide = 0;
    }

    // Update carousel content
    updateCarouselContent(data);
    updateCarouselIndicators(data);
}

function updateCarouselContent(data) {
    const mainCarousel = document.getElementById('mainCarousel');
    const sideCarousel1 = document.getElementById('sideCarousel1');
    const sideCarousel2 = document.getElementById('sideCarousel2');

    if (!mainCarousel) return;

    // Get current, next, and previous halte data
    const currentHalte = data[currentMainSlide];
    const nextHalte = data[(currentMainSlide + 1) % data.length];
    const prevHalte = data[(currentMainSlide - 1 + data.length) % data.length];

    // Main carousel content
    mainCarousel.innerHTML = createCarouselCard(currentHalte, 'main');

    // Side carousels content
    if (data.length > 1 && sideCarousel1 && sideCarousel2) {
        sideCarousel1.innerHTML = createCarouselCard(nextHalte, 'side');
        sideCarousel2.innerHTML = createCarouselCard(prevHalte, 'side');

        // Add click handlers to side carousels
        sideCarousel1.onclick = () => expandCarouselCard(sideCarousel1, () => window.nextMainSlide());
        sideCarousel2.onclick = () => expandCarouselCard(sideCarousel2, () => window.prevMainSlide());
    } else if (sideCarousel1 && sideCarousel2) {
        sideCarousel1.innerHTML = '';
        sideCarousel2.innerHTML = '';
    }
}

function createCarouselCard(halte, type = 'main') {
    const primaryPhoto = halte.photos && halte.photos.length > 0
        ? halte.photos.find(p => p.is_primary) || halte.photos[0]
        : null;

    const photoUrl = primaryPhoto
        ? `/storage/${primaryPhoto.photo_path}`
        : '/images/halte-default.png';

    const isRented = halte.rentals && halte.rentals.some(rental =>
        new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
    );

    const statusClass = isRented ? 'status-rented' : 'status-available';
    const statusText = isRented ? 'Disewa' : 'Tersedia';

    // Check if user is admin (this should be passed from backend)
    const isAdmin = window.isUserAdmin || false;
    const isGuest = window.isGuest !== false;

    const detailButton = isAdmin
        ? `<a href="/halte/${halte.id}" class="carousel-btn">
               <i class="fas fa-info-circle me-1"></i>Detail
           </a>`
        : `<button onclick="showAccessModal()" class="carousel-btn">
               <i class="fas fa-info-circle me-1"></i>Detail
           </button>`;

    const photosButton = halte.photos && halte.photos.length > 0
        ? `<button class="carousel-btn" onclick="openPhotoModal(${halte.id}, '${halte.name}', ${JSON.stringify(halte.photos).replace(/"/g, '&quot;')})">
               <i class="fas fa-images me-1"></i>Foto
           </button>`
        : '';

    return `
        <div class="carousel-card">
            <div class="carousel-image-container">
                <img src="${photoUrl}" alt="${halte.name}" class="carousel-image" loading="lazy">

                <div class="carousel-status ${statusClass}">
                    ${statusText}
                </div>

                ${halte.photos && halte.photos.length > 0 ? `
                <div class="carousel-photo-count">
                    <i class="fas fa-images"></i>
                    ${halte.photos.length} Foto
                </div>
                ` : ''}

                <div class="carousel-actions">
                    ${detailButton}
                    ${photosButton}
                </div>
            </div>

            <div class="carousel-info">
                <h3 class="carousel-title">${halte.name}</h3>
                ${halte.description ? `<p class="carousel-description">${halte.description.substring(0, 100)}${halte.description.length > 100 ? '...' : ''}</p>` : ''}

                <div class="carousel-meta">
                    ${halte.address ? `
                    <div class="carousel-location">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        ${halte.address.substring(0, 30)}${halte.address.length > 30 ? '...' : ''}
                    </div>
                    ` : ''}

                    ${halte.simbada_registered ? `
                    <div class="carousel-simbada">
                        <span class="simbada-badge">
                            <i class="fas fa-certificate"></i> SIMBADA
                        </span>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

function updateCarouselIndicators(data) {
    const indicatorsContainer = document.getElementById('carouselIndicators');
    if (!indicatorsContainer) return;

    indicatorsContainer.innerHTML = '';

    for (let i = 0; i < data.length; i++) {
        const indicator = document.createElement('div');
        indicator.className = `carousel-indicator ${i === currentMainSlide ? 'active' : ''}`;
        indicator.onclick = () => window.goToSlide(i);
        indicatorsContainer.appendChild(indicator);
    }
}

function expandCarouselCard(cardElement, callback) {
    // Add expanded class to clicked card
    const card = cardElement.querySelector('.carousel-card');
    if (!card) return;

    card.classList.add('expanded');

    // Add shrunk class to other cards
    const allCards = document.querySelectorAll('.carousel-card');
    allCards.forEach(c => {
        if (c !== card) {
            c.classList.add('shrunk');
        }
    });

    // Reset after animation and execute callback
    setTimeout(() => {
        allCards.forEach(c => {
            c.classList.remove('expanded', 'shrunk');
        });
        callback();
    }, 800);
}

// Carousel navigation functions (exposed to global scope)
window.nextMainSlide = function() {
    const data = getCurrentFilteredData();
    currentMainSlide = (currentMainSlide + 1) % data.length;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
};

window.prevMainSlide = function() {
    const data = getCurrentFilteredData();
    currentMainSlide = (currentMainSlide - 1 + data.length) % data.length;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
};

window.goToSlide = function(index) {
    const data = getCurrentFilteredData();
    currentMainSlide = index;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
};

function getCurrentFilteredData() {
    const activeFilter = document.querySelector('.filter-btn.active');
    const filter = activeFilter ? activeFilter.getAttribute('data-filter') : 'all';

    if (filter === 'all') {
        return halteData;
    }

    return halteData.filter(halte => {
        const isRented = halte.rentals && halte.rentals.some(rental =>
            new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
        );
        const isAvailable = !isRented;

        switch(filter) {
            case 'available':
                return isAvailable;
            case 'rented':
                return isRented;
            case 'simbada':
                return halte.simbada_registered;
            default:
                return true;
        }
    });
}

// Photo modal functions (exposed to global scope)
window.openPhotoModal = function(halteId, halteName, photos) {
    const modal = document.getElementById('photoModal');
    const title = document.getElementById('photoModalTitle');
    const counter = document.getElementById('imageCounter');
    const swiperWrapper = document.getElementById('swiperWrapper');

    if (!modal || !title || !counter || !swiperWrapper) return;

    title.textContent = `Galeri ${halteName}`;

    if (photos && photos.length > 0) {
        // Clear existing slides
        swiperWrapper.innerHTML = '';

        // Create slides
        photos.forEach((photo, index) => {
            const photoUrl = `/storage/${photo.photo_path}`;
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            slide.innerHTML = `
                <img src="${photoUrl}"
                     alt="${halteName} - Foto ${index + 1}"
                     class="modal-carousel-image"
                     onclick="toggleZoom(this)"
                     loading="lazy">
            `;
            swiperWrapper.appendChild(slide);
        });

        // Update counter
        counter.textContent = `1 / ${photos.length}`;

        // Initialize/Update Swiper
        if (photoSwiper) {
            photoSwiper.destroy(true, true);
        }

        if (typeof Swiper !== 'undefined') {
            photoSwiper = new Swiper('#photoSwiper', {
                loop: photos.length > 1,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '">' + (index + 1) + '</span>';
                    },
                },
                keyboard: {
                    enabled: true,
                    onlyInViewport: false,
                },
                mousewheel: {
                    invert: false,
                },
                speed: 600,
                effect: 'slide',
                allowTouchMove: true,
                grabCursor: true,
                on: {
                    slideChange: function () {
                        const currentSlide = this.realIndex + 1;
                        counter.textContent = `${currentSlide} / ${photos.length}`;

                        // Reset zoom on all images
                        const images = document.querySelectorAll('.modal-carousel-image');
                        images.forEach(img => {
                            img.classList.remove('zoomed');
                        });
                    }
                }
            });
        }
    } else {
        const modalBody = document.getElementById('photoModalBody');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="no-photos">
                    <i class="fas fa-camera-retro"></i>
                    <h4>Tidak ada foto tersedia</h4>
                    <p>Foto halte belum tersedia untuk saat ini.</p>
                </div>
            `;
        }
    }

    // Show modal with animation
    modal.style.display = 'block';
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
};

window.closePhotoModal = function() {
    const modal = document.getElementById('photoModal');
    if (!modal) return;

    // Destroy swiper instance
    if (photoSwiper) {
        photoSwiper.destroy(true, true);
        photoSwiper = null;
    }

    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);

    // Restore body scroll
    document.body.style.overflow = 'auto';
};

// Toggle zoom function for images (exposed to global scope)
window.toggleZoom = function(image) {
    const isZoomed = image.classList.contains('zoomed');

    // Reset all images first
    const allImages = document.querySelectorAll('.modal-carousel-image');
    allImages.forEach(img => img.classList.remove('zoomed'));

    // Toggle current image
    if (!isZoomed) {
        image.classList.add('zoomed');
    }
};

// Access modal function with SweetAlert2 (exposed to global scope)
window.showAccessModal = function() {
    if (typeof Swal === 'undefined') {
        alert('Detail halte hanya dapat diakses oleh user yang memiliki akun, silahkan hubungi admin untuk meminta akun.');
        return;
    }

    Swal.fire({
        icon: 'warning',
        title: 'Akses Terbatas!',
        html: `
            <div style="text-align: center;">
                <div style="font-size: 3rem; color: #ffc107; margin: 1rem 0;">
                    <i class="fas fa-lock"></i>
                </div>
                <div style="color: #6c757d; font-size: 1rem; line-height: 1.6; margin-bottom: 1rem;">
                    Detail lengkap halte hanya dapat diakses oleh User yang memiliki akun.
                    <br>Silakan hubungi admin untuk meminta akun.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Login',
        cancelButtonText: '<i class="fas fa-times"></i> Tutup',
        customClass: {
            popup: 'swal2-custom-popup',
            confirmButton: 'swal2-custom-confirm',
            cancelButton: 'swal2-custom-cancel'
        },
        showCloseButton: true,
        focusConfirm: false,
        width: '500px',
        padding: '2rem',
        backdrop: 'rgba(0,0,0,0.7)'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to login page
            window.location.href = '/login';
        }
    });
};

// Modal listeners
function setupModalListeners() {
    // Close modal when clicking outside or pressing ESC
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('photoModal');
        if (modal && event.target === modal) {
            window.closePhotoModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            window.closePhotoModal();
        }

        // Keyboard navigation for main carousel
        if (isCarouselView && halteData.length > 0) {
            if (event.key === 'ArrowLeft') {
                window.prevMainSlide();
            } else if (event.key === 'ArrowRight') {
                window.nextMainSlide();
            }
        }
    });
}

// Auto-play carousel
function startAutoPlay() {
    if (halteData.length > 1) {
        autoPlayInterval = setInterval(() => {
            if (isCarouselView) {
                window.nextMainSlide();
            }
        }, 5000);
    }
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

// Setup auto-play pause/resume
function setupAutoPlayControl() {
    const carouselContainer = document.getElementById('carouselContainer');
    if (carouselContainer) {
        carouselContainer.addEventListener('mouseenter', stopAutoPlay);
        carouselContainer.addEventListener('mouseleave', startAutoPlay);
    }
}

// Back to top functionality
function setupBackToTop() {
    const backToTop = document.getElementById('backToTop');
    if (!backToTop) return;

    const throttledScroll = throttle(function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    }, 100);

    window.addEventListener('scroll', throttledScroll);

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Load more functionality
function setupLoadMore() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', function() {
        const loadText = this.querySelector('.load-text');
        const spinner = this.querySelector('.loading-spinner');

        if (loadText && spinner) {
            loadText.style.display = 'none';
            spinner.style.display = 'inline-block';

            // Simulate loading
            setTimeout(() => {
                loadText.style.display = 'inline';
                spinner.style.display = 'none';
            }, 2000);
        }
    });
}

// Touch/swipe support for mobile
function setupTouchSupport() {
    const carouselContainer = document.getElementById('carouselContainer');
    if (!carouselContainer) return;

    carouselContainer.addEventListener('touchstart', function(event) {
        touchStartX = event.changedTouches[0].screenX;
    });

    carouselContainer.addEventListener('touchend', function(event) {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    });
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            window.nextMainSlide();
        } else {
            window.prevMainSlide();
        }
    }
}

// Parallax effect for header
function setupParallax() {
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.gallery-header');
        if (header) {
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        }
    });
}

// Intersection observer for animations
function setupIntersectionObserver() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all cards and carousel elements
    document.querySelectorAll('.halte-card, .carousel-card').forEach(element => {
        observer.observe(element);
    });
}

// Preload images for better performance
function preloadImages() {
    halteData.forEach(halte => {
        if (halte.photos && halte.photos.length > 0) {
            halte.photos.forEach(photo => {
                const img = new Image();
                img.src = `/storage/${photo.photo_path}`;
            });
        }
    });
}

// Resize handler for responsive carousel
function setupResizeHandler() {
    window.addEventListener('resize', throttle(function() {
        if (isCarouselView && halteData.length > 0) {
            setTimeout(() => {
                updateCarouselContent(getCurrentFilteredData());
            }, 100);
        }
    }, 250));
}

// Performance optimization: throttle function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Error handling for missing images
function setupImageErrorHandling() {
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            handleImageError(e.target);
        }
    }, true);
}

function handleImageError(img) {
    img.src = '/images/halte-default.png';
    img.alt = 'Gambar tidak tersedia';
}

// Loading states
function showLoading() {
    const carouselContainer = document.getElementById('carouselContainer');
    if (carouselContainer) {
        carouselContainer.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 600px;">
                <div class="loading-spinner"></div>
                <span style="margin-left: 15px; color: var(--dishub-blue); font-weight: 600;">Memuat galeri...</span>
            </div>
        `;
    }
}

// Setup auto-play control after DOM is ready
setTimeout(() => {
    setupAutoPlayControl();
}, 100);
