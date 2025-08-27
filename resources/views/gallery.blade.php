@extends('layouts.app')

@section('title', 'Galeri Halte Bus - E-HalteDishub')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --accent-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        --light-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        --dishub-blue: #1e3c72;
        --dishub-accent: #3b82f6;
    }

    /* Header Section */
    .gallery-header {
        background: var(--primary-gradient);
        color: white;
        padding: 120px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .gallery-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.08"><polygon points="0,0 0,100 1000,80 1000,0"/></svg>');
        background-size: cover;
    }

    .gallery-header-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .gallery-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .gallery-subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 10px 25px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-custom a {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        opacity: 1;
    }

    .breadcrumb-custom .separator {
        opacity: 0.6;
    }

    /* Gallery Section */
    .gallery-section {
        padding: 80px 0;
        background: var(--light-gradient);
    }

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 50px;
    }

    .filter-btn {
        background: white;
        color: var(--dishub-blue);
        border: 2px solid var(--dishub-blue);
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--dishub-blue);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .halte-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.1);
        transition: all 0.4s ease;
        border: 1px solid rgba(59, 130, 246, 0.1);
        position: relative;
    }

    .halte-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 25px 50px rgba(30, 60, 114, 0.2);
    }

    .halte-image-container {
        position: relative;
        height: 280px;
        overflow: hidden;
    }

    .halte-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .halte-card:hover .halte-image {
        transform: scale(1.1);
    }

    .halte-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .status-available {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .status-rented {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .photo-count {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .halte-info {
        padding: 25px;
    }

    .halte-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dishub-blue);
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .halte-description {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .halte-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .halte-location {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .halte-simbada {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
    }

    .simbada-badge {
        background: #28a745;
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .halte-actions {
        display: flex;
        gap: 10px;
    }

    .btn-view-detail {
        flex: 1;
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-view-detail:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    .btn-view-photos {
        background: var(--secondary-gradient);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(29, 78, 216, 0.3);
        cursor: pointer;
    }

    .btn-view-photos:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(29, 78, 216, 0.4);
    }

    /* Photo Modal */
    .photo-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
    }

    .photo-modal-content {
        position: relative;
        width: 90%;
        max-width: 800px;
        margin: 50px auto;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .photo-modal-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .photo-modal-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin: 0;
    }

    .photo-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .photo-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .photo-modal-body {
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
    }

    .photo-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        padding: 25px;
    }

    .photo-gallery-item {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .photo-gallery-item:hover {
        transform: scale(1.05);
    }

    .photo-gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .no-photos {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .no-photos i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    /* Back to Top Button */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--accent-gradient);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-title {
            font-size: 2.5rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .filter-buttons {
            flex-direction: column;
            align-items: center;
        }

        .halte-actions {
            flex-direction: column;
        }

        .photo-modal-content {
            width: 95%;
            margin: 20px auto;
        }
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 3px solid rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        border-top-color: var(--dishub-accent);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
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
                <i class="fas fa-th me-2"></i>Semua Halte
            </button>
            <button class="filter-btn" data-filter="available">
                <i class="fas fa-check-circle me-2"></i>Tersedia
            </button>
            <button class="filter-btn" data-filter="rented">
                <i class="fas fa-clock me-2"></i>Disewa
            </button>
            <button class="filter-btn" data-filter="simbada">
                <i class="fas fa-certificate me-2"></i>SIMBADA Terdaftar
            </button>
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="galleryGrid">
            @forelse($haltes as $halte)
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
                    <div class="photo-count">
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
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-bus" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="color: #6c757d;">Belum ada halte yang terdaftar</h3>
                    <p style="color: #6c757d;">Data halte akan muncul setelah ditambahkan oleh administrator.</p>
                </div>
            </div>
            @endforelse
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
    </div>
</section>

<!-- Photo Modal -->
<div id="photoModal" class="photo-modal">
    <div class="photo-modal-content">
        <div class="photo-modal-header">
            <h3 class="photo-modal-title" id="photoModalTitle">Foto Halte</h3>
            <button class="photo-modal-close" onclick="closePhotoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-modal-body" id="photoModalBody">
            <!-- Photos will be loaded here dynamically -->
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Access Restricted Modal -->
<div class="modal fade" id="accessRestrictedModal" tabindex="-1" aria-labelledby="accessRestrictedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-gradient); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="accessRestrictedModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Akses Terbatas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="padding: 2rem; text-align: center;">
                <div style="font-size: 4rem; color: #ffc107; margin-bottom: 1rem;">
                    <i class="fas fa-lock"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #495057; margin-bottom: 1rem;">
                    Maaf, Akses Dibatasi!
                </div>
                <div style="color: #6c757d; font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;">
                    Detail lengkap halte hanya dapat diakses oleh <strong>Admin</strong>.
                    Silakan login untuk melihat informasi detail halte.
                </div>
            </div>
            <div class="modal-footer justify-content-center" style="border-top: 1px solid #dee2e6; padding: 1rem 2rem; background-color: #f8f9fa; border-radius: 0 0 15px 15px;">
                @guest
                <a href="{{ route('login') }}" class="btn" style="background: var(--secondary-gradient); border: none; padding: 12px 30px; border-radius: 30px; color: white; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(29, 78, 216, 0.3); margin-right: 10px;">
                    <i class="fas fa-sign-in-alt me-1"></i> Login sebagai User
                </a>
                @endguest
                <button type="button" class="btn" data-bs-dismiss="modal" style="background: var(--accent-gradient); border: none; padding: 12px 30px; border-radius: 30px; color: white; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-check me-1"></i> Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn[data-filter]');
    const halteCards = document.querySelectorAll('.halte-card[data-filter]');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Filter cards
            halteCards.forEach(card => {
                const cardFilters = card.getAttribute('data-filter').split(' ');

                if (filter === 'all' || cardFilters.includes(filter)) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Back to top button
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Smooth scrolling for anchor links
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
});

// Photo modal functions
function openPhotoModal(halteId, halteName, photos) {
    const modal = document.getElementById('photoModal');
    const title = document.getElementById('photoModalTitle');
    const body = document.getElementById('photoModalBody');

    title.textContent = `Foto ${halteName}`;

    if (photos && photos.length > 0) {
        let photosHtml = '<div class="photo-gallery-grid">';
        photos.forEach(photo => {
            const photoUrl = `/storage/${photo.photo_path}`;
            photosHtml += `
                <div class="photo-gallery-item">
                    <img src="${photoUrl}" alt="${halteName}" onclick="openLightbox('${photoUrl}', '${halteName}')" loading="lazy">
                </div>
            `;
        });
        photosHtml += '</div>';
        body.innerHTML = photosHtml;
    } else {
        body.innerHTML = `
            <div class="no-photos">
                <i class="fas fa-image"></i>
                <h4>Tidak ada foto tersedia</h4>
                <p>Foto halte belum tersedia untuk saat ini.</p>
            </div>
        `;
    }

    modal.style.display = 'block';
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('photoModal');
    if (event.target === modal) {
        closePhotoModal();
    }
});

// Access modal function
function showAccessModal() {
    const modal = new bootstrap.Modal(document.getElementById('accessRestrictedModal'));
    modal.show();
}

// Lightbox function (optional enhancement)
function openLightbox(imageSrc, altText) {
    // Simple lightbox implementation
    const lightbox = document.createElement('div');
    lightbox.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    `;

    const img = document.createElement('img');
    img.src = imageSrc;
    img.alt = altText;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    `;

    lightbox.appendChild(img);
    document.body.appendChild(lightbox);

    lightbox.addEventListener('click', () => {
        document.body.removeChild(lightbox);
    });
}
</script>
@endpush
