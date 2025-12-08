@extends('layouts.app')

@section('title', 'E-HalteDishub - Sistem Manajemen Halte Bus')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Bootstrap Modal CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- AOS Animation CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Custom Home CSS via Vite -->
@vite('resources/css/home.css')
@endpush

@section('content')
<!-- Hero Section with Slider -->
<section class="hero-section" data-aos="fade-up">
    <div class="hero-slider">
        <!-- Slide 1 -->
        <div class="hero-slide active" style="background-image: url('{{ asset('DISHUB SURABAYA.png') }}');">
        </div>
        <!-- Slide 2 -->
        <div class="hero-slide" style="background-image: url('{{ asset('DISHUB SURABAYA5.jpg') }}');">
        </div>
        <!-- Slide 3 -->
        <div class="hero-slide" style="background-image: url('{{ asset('DISHUB SURABAYA3.jpg') }}');">
        </div>
        <!-- Slide 4 -->
        <div class="hero-slide" style="background-image: url('{{ asset('DISHUB SURABAYA4.jpg') }}');">
        </div>
        <!-- Slide 5 -->
        <div class="hero-slide" style="background-image: url('{{ asset('DISHUB SURABAYA6.jpg') }}');">
        </div>
    </div>

    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Sistem Manajemen Halte Bus</h1>
            <p class="hero-subtitle">Kelola dan pantau halte bus dengan E-HalteDishub yang modern dan efisien</p>

            <div class="hero-stats">
                <div class="hero-stat" data-aos="fade-up" data-aos-delay="100">
                    <span class="hero-stat-number">{{ $statistics['total'] }}</span>
                    <span class="hero-stat-label">Total Halte</span>
                </div>
                <div class="hero-stat" data-aos="fade-up" data-aos-delay="200">
                    <span class="hero-stat-number">{{ $statistics['available'] }}</span>
                    <span class="hero-stat-label">Tersedia</span>
                </div>
                <div class="hero-stat" data-aos="fade-up" data-aos-delay="300">
                    <span class="hero-stat-number">{{ $statistics['rented'] }}</span>
                    <span class="hero-stat-label">Disewa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Navigation Dots -->
    <div class="hero-nav">
        <div class="hero-nav-dot active" data-slide="0"></div>
        <div class="hero-nav-dot" data-slide="1"></div>
        <div class="hero-nav-dot" data-slide="2"></div>
        <div class="hero-nav-dot" data-slide="3"></div>
        <div class="hero-nav-dot" data-slide="4"></div>
    </div>
</section>

<!-- Features Section -->
<section class="zigzag-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Fitur Unggulan Sistem</h2>
                <p class="lead mb-4">Sistem E-HalteDishub menyediakan berbagai fitur canggih untuk memudahkan pengelolaan halte bus di seluruh wilayah.</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="feature-card">
                            <div class="feature-icon primary">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h5>Peta Interaktif</h5>
                            <p>Lokasi halte dalam peta real-time dengan informasi status terkini.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="feature-card">
                            <div class="feature-icon success">
                                <i class="fas fa-bus"></i>
                            </div>
                            <h5>E-HalteDishub</h5>
                            <p>Manajemen dan Mengelola Halte lebih mudah dengan fitur yang tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="photo-gallery">
                    <div class="photo-item">
                        <img src="{{ asset('busid.jpg') }}" alt="Halte Bus Modern" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                        <div class="photo-caption">Trans Surabaya Bus at City Hall</div>
                    </div>
                    <div class="photo-item">
                        <img src="{{ asset('busid4.jpg') }}" alt="Sistem Transportasi" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                        <div class="photo-caption">Surabaya's WiraWiri on Tunjungan Street</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="zigzag-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <h2 class="display-5 fw-bold mb-4">Statistik Real-time</h2>
                <p class="lead mb-4">Pantau status dan ketersediaan halte bus secara real-time melalui dashboard yang informatif.</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="feature-card">
                            <div class="feature-icon warning">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h5>Dashboard Analytics</h5>
                            <p>Analisis mendalam tentang penggunaan dan status halte bus.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="feature-card">
                            <div class="feature-icon primary">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5>Keamanan Data</h5>
                            <p>Sistem keamanan berlapis untuk melindungi data sensitif.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="stat-card">
                            <h3>{{ $statistics['total'] }}</h3>
                            <p class="mb-0"><i class="fas fa-bus"></i> Total Halte Terdaftar</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="stat-card available">
                            <h3>{{ $statistics['available'] }}</h3>
                            <p class="mb-0"><i class="fas fa-check-circle"></i> Tersedia</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="stat-card rented">
                            <h3>{{ $statistics['rented'] }}</h3>
                            <p class="mb-0"><i class="fas fa-clock"></i> Disewa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="zigzag-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-4">Peta Lokasi Halte Bus</h2>
            <p class="lead">Jelajahi lokasi halte bus di seluruh wilayah dengan peta interaktif dan fitur pencarian</p>
        </div>

        <div class="map-container" data-aos="zoom-in">
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
</section>

<!-- Gallery Section Preview -->
<section class="zigzag-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-4">Galeri Halte Bus</h2>
            <p class="lead">Lihat foto-foto halte bus terbaru dengan fasilitas modern di seluruh kota</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="photo-item">
                    <img src="{{ asset('halte.jpg') }}" alt="Halte Bus Kota" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Bus Bungurasih</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="photo-item">
                    <img src="{{ asset('halte2.jpg') }}" alt="Stasiun Bus" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1580274455191-1c62238fa333?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Bus Pasar Turi</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="photo-item">
                    <img src="{{ asset('halte1.jpg') }}" alt="Halte Ramah Disabilitas" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Adityawarman</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="photo-item">
                    <img src="{{ asset('halte3.jpg') }}" alt="Halte Bus Kota" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Unair</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="photo-item">
                    <img src="{{ asset('busid9.jpg') }}" alt="Stasiun Bus" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1580274455191-1c62238fa333?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Joyoboyo</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="photo-item">
                    <img src="{{ asset('halte6.jpg') }}" alt="Halte Ramah Disabilitas" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Dukuh Menanggal</div>
                </div>
            </div>
        </div>

        <div class="text-center" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('gallery') }}" class="gallery-button">
                <i class="fas fa-images me-2"></i>Lihat Galeri Lengkap
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" data-aos="fade-up">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Siap Melihat Halte Bus?</h2>
        <p class="lead mb-4">Bergabunglah dengan Galerry halte bus terdepan untuk efisiensi maksimal</p>
    </div>
</section>

@endsection

@push('scripts')
<!-- jQuery (Required for home.js) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Pass data from Laravel to JavaScript -->
<script>
    // Make data available globally for home.js
    window.isAdmin = @json(auth()->check() && auth()->user()->isAdmin());
    window.haltesData = @json($haltesData);
</script>

<!-- Custom Home JS - Inline -->
<script>
    {!! file_get_contents(resource_path('js/home.js')) !!}
</script>
@endpush
