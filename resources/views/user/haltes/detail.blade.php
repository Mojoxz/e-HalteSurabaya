@extends('layouts.user')

@section('title', $halte->name)
@section('page-title', 'Detail Halte')

@push('styles')
<style>
    :root {
        --primary-dark: #1a1d23;
        --secondary-dark: #2d3139;
        --accent-dark: #3d424d;
        --text-primary: #ffffff;
        --text-secondary: #a8adb7;
        --accent-color: #6c63ff;
        --hover-color: #5650d6;
    }

    body {
        background-color: var(--primary-dark);
        color: var(--text-primary);
    }

    /* Simple Card Styles */
    .detail-card {
        background: var(--secondary-dark);
        border: 1px solid var(--accent-dark);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .detail-card h5 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 18px;
        border-bottom: 1px solid var(--accent-dark);
        padding-bottom: 10px;
    }

    .detail-card h5 i {
        color: var(--accent-color);
        margin-right: 8px;
    }

    .detail-card h6 {
        color: var(--accent-color);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .detail-card p {
        color: var(--text-secondary);
        margin-bottom: 10px;
    }

    .detail-card strong {
        color: var(--text-primary);
    }

    /* Simple Back Button */
    .btn-back {
        background: var(--accent-dark);
        border: 1px solid var(--accent-color);
        color: var(--text-primary);
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-back:hover {
        background: var(--accent-color);
        color: white;
    }

    /* Simple Badge Styles */
    .status-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 13px;
        display: inline-block;
    }

    .status-badge.available {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge.rented {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .simbada-badge {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.3);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .simbada-badge.not-registered {
        background: rgba(107, 114, 128, 0.2);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    /* Simple Photo Gallery */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }

    .photo-item {
        position: relative;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        aspect-ratio: 1;
        border: 1px solid var(--accent-dark);
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-primary-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background: var(--accent-color);
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Simple Document List */
    .document-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .document-item {
        background: var(--accent-dark);
        border: 1px solid var(--primary-dark);
        border-radius: 4px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .document-icon {
        width: 40px;
        height: 40px;
        background: var(--accent-color);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
    }

    .document-info {
        flex: 1;
    }

    .document-name {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .document-meta {
        color: var(--text-secondary);
        font-size: 12px;
    }

    .document-actions {
        display: flex;
        gap: 8px;
    }

    .btn-doc {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-doc-view {
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #3b82f6;
    }

    .btn-doc-view:hover {
        background: rgba(59, 130, 246, 0.3);
    }

    .btn-doc-download {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }

    .btn-doc-download:hover {
        background: rgba(16, 185, 129, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: var(--text-secondary);
    }

    /* Simple Map Styles */
    .map-container {
        height: 300px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid var(--accent-dark);
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .btn-map {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 4px;
        font-weight: 600;
        width: 100%;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-map:hover {
        background: var(--hover-color);
        color: white;
    }

    /* Simple Info Table */
    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid var(--accent-dark);
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table td {
        padding: 8px 0;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .info-table td:first-child {
        font-weight: 600;
        color: var(--text-primary);
        width: 50%;
    }

    /* Simple Modal Styles */
    .modal-content {
        background: var(--secondary-dark);
        border: 1px solid var(--accent-dark);
        border-radius: 8px;
    }

    .modal-header {
        border-bottom: 1px solid var(--accent-dark);
        padding: 15px;
    }

    .modal-title {
        color: var(--text-primary);
        font-weight: 600;
    }

    .modal-body {
        padding: 15px;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.7;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .photo-gallery {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }

        .document-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .document-actions {
            width: 100%;
            margin-top: 8px;
        }

        .btn-doc {
            flex: 1;
            justify-content: center;
        }

        .map-container {
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('user.haltes.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Halte
        </a>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="detail-card">
                <h5>
                    <i class="fas fa-bus"></i>
                    {{ $halte->name }}
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Informasi Lokasi</h6>
                        <p><strong>Alamat:</strong><br>{{ $halte->address }}</p>
                        <p><strong>Koordinat:</strong><br>
                            <code style="background: var(--accent-dark); padding: 2px 6px; border-radius: 3px; color: var(--accent-color);">
                                {{ $halte->latitude }}, {{ $halte->longitude }}
                            </code>
                        </p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h6>Status Halte</h6>
                        @php
                            $isRented = $halte->isCurrentlyRented();
                        @endphp
                        <p>
                            <span class="status-badge {{ $isRented ? 'rented' : 'available' }}">
                                <i class="fas {{ $isRented ? 'fa-calendar' : 'fa-check-circle' }}"></i>
                                {{ $isRented ? 'Sedang Disewa' : 'Tersedia' }}
                            </span>
                        </p>

                        @if($isRented)
                        <p><strong>Disewa oleh:</strong><br>{{ $halte->rented_by }}</p>
                        <p><strong>Periode Sewa:</strong><br>
                            {{ $halte->rent_start_date ? $halte->rent_start_date->format('d/m/Y') : '-' }} s/d
                            {{ $halte->rent_end_date ? $halte->rent_end_date->format('d/m/Y') : 'Tidak terbatas' }}
                        </p>
                        @endif
                    </div>
                </div>

                @if($halte->description)
                <div class="mb-3">
                    <h6>Deskripsi</h6>
                    <p>{{ $halte->description }}</p>
                </div>
                @endif

                @if($halte->simbada_registered)
                <div>
                    <h6>Informasi SIMBADA</h6>
                    <p>
                        <span class="simbada-badge">
                            <i class="fas fa-check-circle"></i> Terdaftar SIMBADA
                        </span>
                    </p>
                    <p><strong>Nomor SIMBADA:</strong> {{ $halte->simbada_number ?: '-' }}</p>
                </div>
                @endif
            </div>

            <!-- Photos Gallery -->
            @if($halte->photos->count() > 0)
            <div class="detail-card">
                <h5>
                    <i class="fas fa-camera"></i>
                    Foto Halte
                    <span class="simbada-badge">{{ $halte->photos->count() }}</span>
                </h5>

                <div class="photo-gallery">
                    @foreach($halte->photos as $photo)
                        @if(file_exists(storage_path('app/public/' . $photo->photo_path)))
                        <div class="photo-item"
                             data-bs-toggle="modal"
                             data-bs-target="#photoModal"
                             data-bs-src="{{ asset('storage/' . $photo->photo_path) }}"
                             data-bs-caption="{{ $photo->description ?: 'Foto ' . $halte->name }}">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                 alt="Foto {{ $halte->name }}">

                            @if($photo->is_primary)
                            <span class="photo-primary-badge">
                                <i class="fas fa-star"></i> Utama
                            </span>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- SIMBADA Documents Section -->
            @php
                $simbadaDocs = $halte->documents()->where('document_type', 'simbada')->get() ?? collect();
                $otherDocs = $halte->documents()->where('document_type', 'other')->get() ?? collect();
            @endphp

            @if($simbadaDocs->count() > 0)
            <div class="detail-card">
                <h5>
                    <i class="fas fa-file-certificate"></i>
                    Dokumen SIMBADA
                    <span class="simbada-badge">{{ $simbadaDocs->count() }}</span>
                </h5>

                <div class="document-list">
                    @foreach($simbadaDocs as $document)
                    <div class="document-item">
                        <div class="document-icon" style="background: #3b82f6;">
                            @if($document->isPdf())
                                <i class="fas fa-file-pdf"></i>
                            @elseif($document->isImage())
                                <i class="fas fa-file-image"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>

                        <div class="document-info">
                            <div class="document-name">{{ $document->document_name }}</div>
                            <div class="document-meta">
                                <span><i class="fas fa-certificate"></i> SIMBADA</span>
                                <span><i class="fas fa-file"></i> {{ strtoupper($document->file_type) }}</span>
                                <span><i class="fas fa-hdd"></i> {{ number_format($document->file_size / 1024, 2) }} KB</span>
                            </div>
                            @if($document->description)
                            <div class="document-meta" style="margin-top: 4px;">
                                <span><i class="fas fa-info-circle"></i> {{ $document->description }}</span>
                            </div>
                            @endif
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
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Other Documents Section -->
            @if($otherDocs->count() > 0)
            <div class="detail-card">
                <h5>
                    <i class="fas fa-file-alt"></i>
                    Dokumen Lainnya
                    <span class="simbada-badge">{{ $otherDocs->count() }}</span>
                </h5>

                <div class="document-list">
                    @foreach($otherDocs as $document)
                    <div class="document-item">
                        <div class="document-icon">
                            @if($document->isPdf())
                                <i class="fas fa-file-pdf"></i>
                            @elseif($document->isImage())
                                <i class="fas fa-file-image"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>

                        <div class="document-info">
                            <div class="document-name">{{ $document->document_name }}</div>
                            <div class="document-meta">
                                <span><i class="fas fa-folder"></i> Dokumen Umum</span>
                                <span><i class="fas fa-file"></i> {{ strtoupper($document->file_type) }}</span>
                                <span><i class="fas fa-hdd"></i> {{ number_format($document->file_size / 1024, 2) }} KB</span>
                            </div>
                            @if($document->description)
                            <div class="document-meta" style="margin-top: 4px;">
                                <span><i class="fas fa-info-circle"></i> {{ $document->description }}</span>
                            </div>
                            @endif
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
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Rental Documents Section -->
            @php
                $rentalDocs = collect();
                if($halte->rentalHistories) {
                    foreach($halte->rentalHistories as $rental) {
                        if($rental->documents) {
                            $rentalDocs = $rentalDocs->merge($rental->documents);
                        }
                    }
                }
            @endphp

            @if($rentalDocs->count() > 0)
            <div class="detail-card">
                <h5>
                    <i class="fas fa-file-contract"></i>
                    Dokumen Sewa/Rental
                    <span class="simbada-badge">{{ $rentalDocs->count() }}</span>
                </h5>

                <div class="document-list">
                    @foreach($rentalDocs as $document)
                    <div class="document-item">
                        <div class="document-icon" style="background: #f59e0b;">
                            @if($document->isPdf())
                                <i class="fas fa-file-pdf"></i>
                            @elseif($document->isImage())
                                <i class="fas fa-file-image"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>

                        <div class="document-info">
                            <div class="document-name">{{ $document->document_name }}</div>
                            <div class="document-meta">
                                <span><i class="fas fa-handshake"></i> Dokumen Rental</span>
                                <span><i class="fas fa-file"></i> {{ strtoupper($document->file_type) }}</span>
                                <span><i class="fas fa-hdd"></i> {{ number_format($document->file_size / 1024, 2) }} KB</span>
                            </div>
                            @if($document->description)
                            <div class="document-meta" style="margin-top: 4px;">
                                <span><i class="fas fa-info-circle"></i> {{ $document->description }}</span>
                            </div>
                            @endif
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
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Map -->
            <div class="detail-card">
                <h5>
                    <i class="fas fa-map-marker-alt"></i>
                    Lokasi di Peta
                </h5>

                <div class="map-container mb-3">
                    <iframe
                        src="https://maps.google.com/maps?q={{ $halte->latitude }},{{ $halte->longitude }}&hl=id&z=15&output=embed"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <a href="https://maps.google.com/?q={{ $halte->latitude }},{{ $halte->longitude }}"
                   target="_blank"
                   class="btn-map">
                    <i class="fas fa-external-link-alt"></i>
                    Buka di Google Maps
                </a>
            </div>

            <!-- Quick Info -->
            <div class="detail-card">
                <h5>
                    <i class="fas fa-info-circle"></i>
                    Informasi Singkat
                </h5>

                <table class="info-table">
                    <tr>
                        <td>ID Halte</td>
                        <td>{{ $halte->id }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            <span class="status-badge {{ $isRented ? 'rented' : 'available' }}" style="font-size: 11px;">
                                {{ $isRented ? 'Disewa' : 'Tersedia' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Jumlah Foto</td>
                        <td>{{ $halte->photos->count() }}</td>
                    </tr>
                    <tr>
                        <td>Dokumen SIMBADA</td>
                        <td>{{ $simbadaDocs->count() }}</td>
                    </tr>
                    <tr>
                        <td>Dokumen Lainnya</td>
                        <td>{{ $otherDocs->count() }}</td>
                    </tr>
                    <tr>
                        <td>Dokumen Rental</td>
                        <td>{{ $rentalDocs->count() }}</td>
                    </tr>
                    <tr>
                        <td>SIMBADA</td>
                        <td>
                            @if($halte->simbada_registered)
                            <span class="simbada-badge" style="font-size: 11px;">
                                <i class="fas fa-check"></i> Terdaftar
                            </span>
                            @else
                            <span class="simbada-badge not-registered" style="font-size: 11px;">
                                <i class="fas fa-times"></i> Belum
                            </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Halte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" alt="Foto Halte" id="modalImage">
                <p class="mt-3" id="modalCaption" style="color: var(--text-secondary);"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo modal handler
    const photoModal = document.getElementById('photoModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');

    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-bs-src');
            const caption = button.getAttribute('data-bs-caption');

            modalImage.src = src;
            modalCaption.textContent = caption;
        });
    }
});
</script>
@endpush
