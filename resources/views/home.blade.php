@extends('layouts.app')

@section('title', 'E-HalteDishub - Sistem Manajemen Halte Bus')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Bootstrap Modal CSS (jika belum ada) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --accent-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        --light-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        --glass-bg: rgba(255, 255, 255, 0.15);
        --glass-border: rgba(255, 255, 255, 0.25);
        --dishub-blue: #1e3c72;
        --dishub-light-blue: #e1f5fe;
        --dishub-accent: #3b82f6;
    }

    /* Hero Section */
    .hero-section {
        background: var(--primary-gradient);
        color: white;
        padding: 120px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.08"><polygon points="0,0 0,100 1000,80 1000,0"/></svg>');
        background-size: cover;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 3.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        background: linear-gradient(45deg, #ffffff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.4rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        font-weight: 300;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        justify-content: center;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .hero-stat {
        text-align: center;
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        min-width: 180px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hero-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .hero-stat-number {
        font-size: 3rem;
        font-weight: 700;
        display: block;
        color: white;
    }

    .hero-stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* Zigzag Sections */
    .zigzag-section {
        padding: 100px 0;
        position: relative;
    }

    .zigzag-section:nth-child(even) {
        background: var(--light-gradient);
    }

    .zigzag-section:nth-child(odd) {
        background: white;
    }

    .zigzag-section::before {
        content: '';
        position: absolute;
        top: -1px;
        left: 0;
        right: 0;
        height: 80px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" fill="%23f8fafc"><path d="M0,0V46.29C47.79,22.41,103.59,32.17,158,28,227.36,21.54,292.82,32.58,327.8,45.68V0Z" opacity="0.25"></path><path d="M1200,0V15.81C1126.52,33.84,1021.86,31.68,938,17.57,765.94,-2.94,684.46,20.61,504.8,19.75,391.54,19.23,289.69,7.06,210.2,21.85,144.46,33.9,75.39,41.51,0,38.73V0Z" opacity="0.5"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475,48.92,640.87,26.15,800.18,5.27,1200,5.76V0Z"></path></svg>');
        background-size: cover;
    }

    /* Feature Cards */
    .feature-card {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 1px solid rgba(59, 130, 246, 0.1);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.15);
    }

    .feature-card:hover::before {
        transform: scaleX(1);
    }

    .feature-icon {
        width: 90px;
        height: 90px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .feature-icon.primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
    }
    .feature-icon.success {
        background: var(--accent-gradient);
        color: white;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    .feature-icon.warning {
        background: var(--secondary-gradient);
        color: white;
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.3);
    }

    /* Statistics Cards */
    .stat-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 25px;
        padding: 2.5rem 2rem;
        text-align: center;
        margin-bottom: 1rem;
        transition: all 0.4s ease;
        border: none;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.2);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.3);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.available {
        background: var(--accent-gradient);
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
    }

    .stat-card.available:hover {
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
    }

    .stat-card.rented {
        background: var(--secondary-gradient);
        box-shadow: 0 10px 30px rgba(29, 78, 216, 0.2);
    }

    .stat-card.rented:hover {
        box-shadow: 0 20px 40px rgba(29, 78, 216, 0.3);
    }

    .stat-card h3 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .stat-card p {
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.95;
    }

    /* Photo Gallery */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 2.5rem;
    }

    .photo-item {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(30, 60, 114, 0.1);
        transition: all 0.4s ease;
        background: white;
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .photo-item:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.2);
    }

    .photo-item img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .photo-item:hover img {
        transform: scale(1.05);
    }

    .photo-caption {
        padding: 1.5rem;
        background: white;
        font-weight: 600;
        color: var(--dishub-blue);
        font-size: 1.05rem;
        text-align: center;
    }

    /* Map Section */
    #map {
        height: 600px;
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .map-container {
        position: relative;
    }

    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 15px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 1000;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .legend-available { background-color: #28a745; }
    .legend-rented { background-color: #dc3545; }

    /* CTA Section */
    .cta-section {
        background: var(--primary-gradient);
        color: white;
        padding: 100px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.05"><circle cx="100" cy="50" r="30"/><circle cx="300" cy="80" r="20"/><circle cx="500" cy="30" r="25"/><circle cx="700" cy="70" r="35"/><circle cx="900" cy="40" r="20"/></svg>');
        background-size: cover;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .cta-button {
        background: white;
        color: var(--dishub-blue);
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        display: inline-block;
        margin-top: 2rem;
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 2;
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(255, 255, 255, 0.3);
        color: var(--dishub-blue);
        background: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .zigzag-section {
            padding: 60px 0;
        }
    }

    /* Custom popup styles */
    .leaflet-popup-content {
        width: 350px !important;
        margin: 0 !important;
    }
    .leaflet-popup-content-wrapper {
        padding: 0 !important;
    }

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
    .popup-actions {
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        text-align: center;
    }
    .btn-detail {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 10px 24px;
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

    /* Modal styles */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background: var(--primary-gradient);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .modal-header .btn-close {
        filter: invert(1);
    }
    .modal-body {
        padding: 2rem;
        text-align: center;
    }
    .modal-icon {
        font-size: 4rem;
        color: #ffc107;
        margin-bottom: 1rem;
    }
    .modal-title-custom {
        font-size: 1.5rem;
        font-weight: bold;
        color: #495057;
        margin-bottom: 1rem;
    }
    .modal-text {
        color: #6c757d;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem 2rem;
        background-color: #f8f9fa;
        border-radius: 0 0 15px 15px;
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
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section" data-aos="fade-up">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Sistem Manajemen Halte Bus</h1>
            <p class="hero-subtitle">Kelola dan pantau halte bus dengan E-HalteDishub</p>

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
            <p class="lead">Jelajahi lokasi halte bus di seluruh wilayah dengan peta interaktif</p>
        </div>

        <div class="map-container" data-aos="zoom-in">
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
</section>

<!-- Gallery Section -->
<section class="zigzag-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-4">Galeri Halte Bus</h2>
            <p class="lead">Koleksi foto halte bus terbaru dengan fasilitas modern</p>
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
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="photo-item">
                    <img src="{{ asset('halte3.jpg') }}" alt="Halte Eco Friendly" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Unair</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="photo-item">
                    <img src="{{ asset('halte6.jpg') }}" alt="Smart Bus Stop" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte Dukuh Menanggal</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                <div class="photo-item">
                    <img src="{{ asset('halte5.jpg') }}" alt="Halte Terintegrasi" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                    <div class="photo-caption">Halte ITS</div>
                </div>
            </div>
                </div>
            </div>
            <!--<div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="photo-item">
                    <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Halte Ramah Disabilitas" loading="lazy">
                    <div class="photo-caption">Halte Ramah Disabilitas</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="photo-item">
                    <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Halte Eco Friendly" loading="lazy">
                    <div class="photo-caption">Halte Eco-Friendly dengan Panel Surya</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="photo-item">
                    <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Smart Bus Stop" loading="lazy">
                    <div class="photo-caption">Smart Bus Stop dengan Digital Display</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                <div class="photo-item">
                    <img src="https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Halte Terintegrasi" loading="lazy">
                    <div class="photo-caption">Halte Terintegrasi Multi-Moda</div>
                </div> -->
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" data-aos="fade-up">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Siap Mengelola Halte Bus?</h2>
        <p class="lead mb-4">Bergabunglah dengan sistem manajemen halte bus terdepan untuk efisiensi maksimal</p>
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="cta-button">
                    <i class="fas fa-tachometer-alt me-2"></i>Akses Dashboard Admin
                </a>
            @endif
        @else
        @endauth
    </div>
</section>

<!-- Modal for Access Restriction -->
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
                    Detail lengkap halte hanya dapat diakses oleh <strong>Admin</strong>.
                    Silakan login untuk melihat informasi detail halte.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                @guest
                <a href="{{ route('login') }}" class="btn btn-login-modal me-2">
                    <i class="fas fa-sign-in-alt me-1"></i> Login sebagai User
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
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
$(document).ready(function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    // Check if user is admin
    const isAdmin = @json(auth()->check() && auth()->user()->isAdmin());

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
                const target = parseInt(counter.textContent);
                counter.textContent = '0';
                animateCounter(counter, target);
                counterObserver.unobserve(entry.target);
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

        // Add loading overlay
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
                    <p style="margin-top: 10px; color: #666;">Memuat peta...</p>
                </div>
            </div>
        `;

        mapContainer.appendChild(loadingOverlay);

        // Remove loading overlay after map loads
        map.whenReady(() => {
            setTimeout(() => {
                if (loadingOverlay && loadingOverlay.parentNode) {
                    loadingOverlay.remove();
                }
            }, 1000);
        });
    }
});
</script>
@endpush
