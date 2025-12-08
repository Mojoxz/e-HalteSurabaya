@extends('layouts.app')

@section('title', 'Galeri Halte Bus - E-HalteDishub')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Swiper CSS untuk carousel -->
<link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@vite(['resources/css/gallery.css'])
@endpush

@section('content')
<!-- Gallery Header -->
<section class="gallery-header">
    <div class="container">
        <div class="gallery-header-content">
            <div class="breadcrumb-custom" data-aos="fade-up">
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <span class="separator">/</span>
                <span>Galeri Halte</span>
            </div>
            <h1 class="gallery-title" data-aos="fade-up" data-aos-delay="100">
                Galeri Halte Bus
            </h1>
            <p class="gallery-subtitle" data-aos="fade-up" data-aos-delay="200">
                Jelajahi koleksi foto halte bus di seluruh kota dengan fasilitas modern dan terintegrasi
            </p>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="filter-buttons" data-aos="fade-up">
            <button class="filter-btn active" data-filter="all">
                <span><i class="fas fa-th me-2"></i>Semua Halte</span>
            </button>
            <button class="filter-btn" data-filter="available">
                <span><i class="fas fa-check-circle me-2"></i>Tersedia</span>
            </button>
            <button class="filter-btn" data-filter="rented">
                <span><i class="fas fa-clock me-2"></i>Disewa</span>
            </button>
            <button class="filter-btn" data-filter="simbada">
                <span><i class="fas fa-certificate me-2"></i>SIMBADA Terdaftar</span>
            </button>
        </div>

        <!-- View Toggle -->
        <div class="view-toggle" data-aos="fade-up" data-aos-delay="100">
            <button class="view-toggle-btn active" id="carouselViewBtn">
                <i class="fas fa-film me-2"></i>Tampilan Carousel
            </button>
            <button class="view-toggle-btn" id="gridViewBtn">
                <i class="fas fa-th-large me-2"></i>Tampilan Grid
            </button>
        </div>

        @if($haltes->count() > 0)
            <!-- Main Carousel Container -->
            <div class="main-carousel-container" id="carouselContainer" data-aos="fade-up" data-aos-delay="200">
                <div class="carousel-layout">
                    <!-- Main Carousel -->
                    <div class="main-carousel" id="mainCarousel">
                        <!-- Main carousel content will be populated by JavaScript -->
                    </div>

                    <!-- Side Carousels -->
                    <div class="side-carousel" id="sideCarousel1">
                        <!-- Side carousel 1 content will be populated by JavaScript -->
                    </div>

                    <div class="side-carousel" id="sideCarousel2">
                        <!-- Side carousel 2 content will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Navigation -->
                <button class="main-carousel-nav carousel-prev" onclick="prevMainSlide()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="main-carousel-nav carousel-next" onclick="nextMainSlide()">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators" id="carouselIndicators">
                    <!-- Indicators will be populated by JavaScript -->
                </div>
            </div>

            <!-- Traditional Grid -->
            <div class="traditional-grid" id="traditionalGrid">
                @foreach($haltes as $halte)
                <div class="halte-card"
                     data-filter="{{ $halte->isCurrentlyRented() ? 'rented' : 'available' }} {{ $halte->simbada_registered ? 'simbada' : '' }}"
                     data-aos="fade-up"
                     data-aos-delay="{{ $loop->index * 100 }}">

                    <div class="halte-image-container">
                        @php
                            $primaryPhoto = $halte->photos->where('is_primary', true)->first() ?? $halte->photos->first();
                            $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                                ? asset('storage/' . $primaryPhoto->photo_path)
                                : asset('images/halte-default.png');
                        @endphp

                        <img src="{{ $photoUrl }}" alt="{{ $halte->name }}" class="halte-image" loading="lazy">

                        <div class="halte-status {{ $halte->isCurrentlyRented() ? 'status-rented' : 'status-available' }}">
                            {{ $halte->isCurrentlyRented() ? 'Disewa' : 'Tersedia' }}
                        </div>

                        @if($halte->photos->count() > 0)
                        <div class="photo-count" onclick="openPhotoModal({{ $halte->id }}, '{{ $halte->name }}', {{ $halte->photos->toJson() }})">
                            <i class="fas fa-images"></i>
                            {{ $halte->photos->count() }} Foto
                        </div>
                        @endif
                    </div>

                    <div class="halte-info">
                        <h3 class="halte-title">{{ $halte->name }}</h3>

                        @if($halte->description)
                        <p class="halte-description">{{ $halte->description }}</p>
                        @endif

                        <div class="halte-meta">
                            @if($halte->address)
                            <div class="halte-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ Str::limit($halte->address, 30) }}
                            </div>
                            @endif

                            @if($halte->simbada_registered)
                            <div class="halte-simbada">
                                <span class="simbada-badge">
                                    <i class="fas fa-certificate"></i> SIMBADA
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="halte-actions">
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('halte.detail', $halte->id) }}" class="btn-view-detail">
                                        <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                    </a>
                                @else
                                    <button onclick="showAccessModal()" class="btn-view-detail">
                                        <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                    </button>
                                @endif
                            @else
                                <button onclick="showAccessModal()" class="btn-view-detail">
                                    <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                </button>
                            @endauth

                            @if($halte->photos->count() > 0)
                            <button class="btn-view-photos" onclick="openPhotoModal({{ $halte->id }}, '{{ $halte->name }}', {{ $halte->photos->toJson() }})">
                                <i class="fas fa-images"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if($haltes->count() >= 12)
            <div class="text-center" data-aos="fade-up">
                <button class="filter-btn" id="loadMoreBtn">
                    <span class="load-text">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                    </span>
                    <span class="loading-spinner" style="display: none;"></span>
                </button>
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state" data-aos="fade-up">
                <i class="fas fa-bus"></i>
                <h3>Belum ada halte yang terdaftar</h3>
                <p>Data halte akan muncul setelah ditambahkan oleh administrator.</p>
            </div>
        @endif
    </div>
</section>

<!-- Enhanced Photo Modal with Swiper Carousel -->
<div id="photoModal" class="photo-modal">
    <div class="photo-modal-content">
        <div class="photo-modal-header">
            <h3 class="photo-modal-title" id="photoModalTitle">Foto Halte</h3>
            <button class="photo-modal-close" onclick="closePhotoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-modal-body" id="photoModalBody">
            <div class="photo-carousel-container">
                <!-- Image Counter -->
                <div class="image-counter" id="imageCounter">1 / 1</div>

                <!-- Swiper Container -->
                <div class="swiper photo-swiper" id="photoSwiper">
                    <div class="swiper-wrapper" id="swiperWrapper">
                        <!-- Slides will be populated dynamically -->
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>

                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- Zoom hint -->
                    <div class="zoom-hint">
                        <i class="fas fa-search-plus me-1"></i> Klik untuk zoom
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</button>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Pass data from backend to JavaScript
window.halteData = @json($haltes);
window.isUserAdmin = @json(Auth::check() && Auth::user()->isAdmin());
window.isGuest = @json(!Auth::check());
</script>

@vite(['resources/js/gallery.js'])

<script>
// Initialize gallery when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initGallery === 'function') {
        window.initGallery(window.halteData);
    }
});
</script>
@endpush
