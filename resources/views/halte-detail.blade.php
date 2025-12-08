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
<style>
    .detail-header {
        background: linear-gradient(135deg, #1a4b8c 0%, #2a75d6 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(26, 75, 140, 0.2);
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        animation: fadeInScale 0.5s ease-out;
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
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #dee2e6;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeInScale 0.5s ease-out;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .info-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 1rem 1.25rem;
        border-radius: 12px 12px 0 0;
        transition: background-color 0.3s ease;
    }

    .info-card:hover .card-header {
        background-color: #e6f0fa;
    }

    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }

    .info-row:hover {
        background-color: rgba(230, 240, 250, 0.3);
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
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .photo-item:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .no-photos {
        text-align: center;
        padding: 3rem 0;
        color: #6c757d;
    }

    .map-container {
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0.5rem 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
        color: #0056b3;
    }

    .rental-history-table {
        font-size: 0.875rem;
    }

    .rental-history-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }

    .rental-history-table tr {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .rental-history-table tr:hover {
        background-color: rgba(230, 240, 250, 0.5);
        transform: scale(1.01);
    }

    /* Document Styles */
    .document-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .document-item:hover {
        background: #e6f0fa;
        border-color: #1a4b8c;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(26, 75, 140, 0.1);
    }

    .document-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .document-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1a4b8c 0%, #2a75d6 100%);
        color: white;
        border-radius: 8px;
        margin-right: 1rem;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .document-details {
        flex: 1;
    }

    .document-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
        word-break: break-word;
    }

    .document-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .document-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .btn-doc {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-doc:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-doc-view {
        background: #1a4b8c;
        color: white;
    }

    .btn-doc-view:hover {
        background: #153a73;
        color: white;
    }

    .btn-doc-download {
        background: #155724;
        color: white;
    }

    .btn-doc-download:hover {
        background: #0d3d1a;
        color: white;
    }

    .no-documents {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .no-documents i {
        font-size: 2.5rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .document-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .document-actions {
            width: 100%;
            margin-top: 0.75rem;
            justify-content: flex-start;
        }

        .btn-doc {
            flex: 1;
        }
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

            <!-- Halte Documents -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-file-alt"></i> Dokumen Halte
                    <span class="badge bg-secondary ms-2">{{ $halte->documents->count() }} dokumen</span>
                </div>
                <div class="card-body">
                    @if($halte->documents->count() > 0)
                        <ul class="document-list">
                            @foreach($halte->documents as $document)
                                <li class="document-item">
                                    <div class="document-info">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="document-details">
                                            <div class="document-name">{{ $document->file_name }}</div>
                                            <div class="document-meta">
                                                <i class="fas fa-clock"></i> {{ $document->created_at->format('d M Y, H:i') }}
                                                @if($document->file_size)
                                                    | <i class="fas fa-hdd"></i> {{ number_format($document->file_size / 1024, 2) }} KB
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ route('admin.haltes.documents.view', $document->id) }}"
                                           class="btn-doc btn-doc-view"
                                           target="_blank">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.haltes.documents.download', $document->id) }}"
                                           class="btn-doc btn-doc-download">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="no-documents">
                            <i class="fas fa-folder-open"></i>
                            <p class="mb-0">Belum ada dokumen yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Map -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt"></i> Lokasi
                </div>
                <div class="card-body p-0">
                    <div id="map" class="map-container">
                        <iframe
                            width="100%"
                            height="400"
                            frameborder="0"
                            style="border:0; display: block;"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ $halte->latitude }},{{ $halte->longitude }}&hl=id&z=16&output=embed"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="p-3 bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $halte->latitude }}, {{ $halte->longitude }}
                            </small>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $halte->latitude }},{{ $halte->longitude }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i> Buka di Google Maps
                            </a>
                        </div>
                    </div>
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
                                    <th>Dokumen</th>
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
                                    <td>
                                        @if($history->documents->count() > 0)
                                            <span class="badge bg-info">
                                                <i class="fas fa-file"></i> {{ $history->documents->count() }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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

            <!-- Rental Documents (from recent rental history) -->
            @if($halte->isCurrentlyRented() && $halte->rentalHistories->first() && $halte->rentalHistories->first()->documents->count() > 0)
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-file-contract"></i> Dokumen Penyewaan Aktif
                    <span class="badge bg-secondary ms-2">{{ $halte->rentalHistories->first()->documents->count() }} dokumen</span>
                </div>
                <div class="card-body">
                    <ul class="document-list">
                        @foreach($halte->rentalHistories->first()->documents as $document)
                            <li class="document-item">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div class="document-details">
                                        <div class="document-name">{{ $document->file_name }}</div>
                                        <div class="document-meta">
                                            <i class="fas fa-clock"></i> {{ $document->created_at->format('d M Y, H:i') }}
                                            @if($document->file_size)
                                                | <i class="fas fa-hdd"></i> {{ number_format($document->file_size / 1024, 2) }} KB
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    <a href="{{ route('admin.rentals.documents.view', $document->id) }}"
                                       class="btn-doc btn-doc-view"
                                       target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('admin.rentals.documents.download', $document->id) }}"
                                       class="btn-doc btn-doc-download">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
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


