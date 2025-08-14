@extends('layouts.app')

@section('title', 'Detail Halte - ' . $halte->name)

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i class="fas fa-home"></i> Beranda
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Detail Halte</li>
@endsection

@section('page-actions')
    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Peta
    </a>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Lightbox2 CSS for photo gallery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
<style>
    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0.375rem;
    }
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    .status-available {
        background-color: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }
    .status-rented {
        background-color: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }
    .info-card {
        background: white;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #dee2e6;
        margin-bottom: 1.5rem;
    }
    .info-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 150px;
        flex-shrink: 0;
    }
    .info-value {
        color: #6c757d;
        flex: 1;
    }
    .photo-gallery {
        margin-bottom: 2rem;
    }
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .photo-item {
        position: relative;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease;
    }
    .photo-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .photo-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
    }
    .primary-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #28a745;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .no-photos {
        text-align: center;
        padding: 3rem 0;
        color: #6c757d;
    }
    .map-container {
        height: 400px;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .breadcrumb {
        background-color: transparent;
        padding: 0.5rem 0;
        margin-bottom: 1rem;
    }
    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
    .rental-history-table {
        font-size: 0.875rem;
    }
    .rental-history-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }
    .back-button {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 500;
        transition: transform 0.2s ease;
    }
    .back-button:hover {
        transform: translateY(-1px);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header -->
    <div class="detail-header text-center mt-4">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">{{ $halte->name }}</h1>
            <div class="status-badge {{ $halte->isCurrentlyRented() ? 'status-rented' : 'status-available' }}">
                <i class="fas {{ $halte->isCurrentlyRented() ? 'fa-clock' : 'fa-check-circle' }}"></i>
                {{ $halte->isCurrentlyRented() ? 'SEDANG DISEWA' : 'TERSEDIA' }}
            </div>
            <p class="lead mb-0">
                {{ $halte->description ?? 'Halte bus dalam sistem manajemen SIMBADA' }}
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Photo Gallery -->
            <div class="photo-gallery">
                <div class="info-card">
                    <div class="card-header">
                        <i class="fas fa-images"></i> Galeri Foto
                        <span class="badge bg-secondary ms-2">{{ $halte->photos->count() }} foto</span>
                    </div>
                    <div class="card-body">
                        @if($halte->photos->count() > 0)
                            <div class="photo-grid">
                                @foreach($halte->photos as $photo)
                                    <div class="photo-item">
                                        <a href="{{ asset('storage/' . $photo->photo_path) }}"
                                           data-lightbox="halte-photos"
                                           data-title="{{ $photo->description ?? $halte->name }}">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                 alt="{{ $photo->description ?? $halte->name }}"
                                                 onerror="this.src='{{ asset('images/halte-default.png') }}'">
                                            @if($photo->is_primary)
                                                <div class="primary-badge">
                                                    <i class="fas fa-star"></i> Utama
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-photos">
                                <i class="fas fa-image" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="mt-3 mb-0">Belum ada foto yang tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt"></i> Lokasi
                </div>
                <div class="card-body p-0">
                    <div id="map" class="map-container"></div>
                </div>
            </div>

            <!-- Rental History -->
            @if($halte->rentalHistories->count() > 0)
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Riwayat Penyewaan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped rental-history-table">
                            <thead>
                                <tr>
                                    <th>Penyewa</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Biaya</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($halte->rentalHistories->take(10) as $history)
                                <tr>
                                    <td>{{ $history->rented_by }}</td>
                                    <td>{{ $history->rent_start_date->format('d/m/Y') }}</td>
                                    <td>{{ $history->rent_end_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($history->rental_cost > 0)
                                            Rp {{ number_format($history->rental_cost, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $history->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($halte->rentalHistories->count() > 10)
                        <p class="text-muted text-center mb-0 mt-3">
                            <small>Menampilkan 10 riwayat terbaru dari {{ $halte->rentalHistories->count() }} total riwayat</small>
                        </p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Basic Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Informasi Dasar
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-tag"></i> Nama:
                        </div>
                        <div class="info-value">{{ $halte->name }}</div>
                    </div>
                    @if($halte->description)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-align-left"></i> Deskripsi:
                        </div>
                        <div class="info-value">{{ $halte->description }}</div>
                    </div>
                    @endif
                    @if($halte->address)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> Alamat:
                        </div>
                        <div class="info-value">{{ $halte->address }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-crosshairs"></i> Koordinat:
                        </div>
                        <div class="info-value">
                            {{ $halte->latitude }}, {{ $halte->longitude }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIMBADA Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-database"></i> Informasi SIMBADA
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-check-square"></i> Status:
                        </div>
                        <div class="info-value">
                            @if($halte->simbada_registered)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Terdaftar
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation"></i> Belum Terdaftar
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($halte->simbada_number)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-hashtag"></i> Nomor:
                        </div>
                        <div class="info-value">{{ $halte->simbada_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Rental Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i> Informasi Penyewaan
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-info"></i> Status:
                        </div>
                        <div class="info-value">
                            <span class="badge {{ $halte->isCurrentlyRented() ? 'bg-danger' : 'bg-success' }}">
                                {{ $halte->isCurrentlyRented() ? 'Sedang Disewa' : 'Tersedia' }}
                            </span>
                        </div>
                    </div>
                    @if($halte->is_rented)
                        @if($halte->rented_by)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-user"></i> Penyewa:
                            </div>
                            <div class="info-value">{{ $halte->rented_by }}</div>
                        </div>
                        @endif
                        @if($halte->rent_start_date)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-play"></i> Mulai:
                            </div>
                            <div class="info-value">{{ $halte->rent_start_date->format('d F Y') }}</div>
                        </div>
                        @endif
                        @if($halte->rent_end_date)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-stop"></i> Berakhir:
                            </div>
                            <div class="info-value">{{ $halte->rent_end_date->format('d F Y') }}</div>
                        </div>
                        @endif
                    @else
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-info-circle"></i> Keterangan:
                            </div>
                            <div class="info-value text-success">
                                Halte ini tersedia untuk disewa
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.haltes.edit', $halte->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Halte
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Lightbox2 JS for photo gallery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize map
    const map = L.map('map').setView([{{ $halte->latitude }}, {{ $halte->longitude }}], 16);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Create marker icon based on rental status
    const markerIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color: {{ $halte->isCurrentlyRented() ? '#dc3545' : '#28a745' }}; width: 30px; height: 30px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.4);"></div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    // Add marker
    L.marker([{{ $halte->latitude }}, {{ $halte->longitude }}], { icon: markerIcon })
        .bindPopup(`
            <div style="text-align: center; min-width: 200px;">
                <h5 style="margin-bottom: 10px;">{{ $halte->name }}</h5>
                <p style="margin-bottom: 5px;">{{ $halte->address ?? 'Lokasi halte bus' }}</p>
                <span class="badge bg-{{ $halte->isCurrentlyRented() ? 'danger' : 'success' }}">
                    {{ $halte->isCurrentlyRented() ? 'Sedang Disewa' : 'Tersedia' }}
                </span>
            </div>
        `)
        .addTo(map)
        .openPopup();

    // Configure lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Foto %1 dari %2'
    });
});
</script>
@endpush
