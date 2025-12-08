@extends('layouts.user')

@section('title', $halte->name)
@section('page-title', 'Detail Halte')

@push('styles')
@vite(['resources/css/user/detail-halte.css'])
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
@vite(['resources/js/user/detail-halte.js'])
@endpush
