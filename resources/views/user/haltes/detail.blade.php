@extends('layouts.user')

@section('title', $halte->name)
@section('page-title', 'Detail Halte')

@push('styles')
<style>
    .photo-gallery img {
        cursor: pointer;
        transition: transform 0.3s;
    }
    .photo-gallery img:hover {
        transform: scale(1.05);
    }
    #mapContainer {
        height: 300px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('user.haltes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Halte
        </a>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">{{ $halte->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Lokasi</h6>
                            <p><strong>Alamat:</strong><br>{{ $halte->address }}</p>
                            <p><strong>Koordinat:</strong><br>{{ $halte->latitude }}, {{ $halte->longitude }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Status Halte</h6>
                            @php
                                $isRented = $halte->isCurrentlyRented();
                            @endphp
                            <p>
                                <span class="badge {{ $isRented ? 'bg-warning' : 'bg-success' }} fs-6">
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
                    <hr>
                    <h6>Deskripsi</h6>
                    <p>{{ $halte->description }}</p>
                    @endif

                    @if($halte->simbada_registered)
                    <hr>
                    <h6>Informasi SIMBADA</h6>
                    <p><strong>Nomor SIMBADA:</strong> {{ $halte->simbada_number ?: '-' }}</p>
                    @endif
                </div>
            </div>

            <!-- Photos Gallery -->
            @if($halte->photos->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-camera"></i> Foto Halte
                        <span class="badge bg-primary">{{ $halte->photos->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row photo-gallery">
                        @foreach($halte->photos as $photo)
                        @if(file_exists(storage_path('app/public/' . $photo->photo_path)))
                        <div class="col-md-4 mb-3">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                 class="img-fluid rounded shadow-sm"
                                 alt="Foto {{ $halte->name }}"
                                 data-bs-toggle="modal"
                                 data-bs-target="#photoModal"
                                 data-bs-src="{{ asset('storage/' . $photo->photo_path) }}"
                                 data-bs-caption="{{ $photo->description ?: 'Foto ' . $halte->name }}">
                            @if($photo->is_primary)
                            <span class="badge bg-primary position-absolute" style="top: 5px; left: 5px;">
                                <i class="fas fa-star"></i> Utama
                            </span>
                            @endif
                            @if($photo->description)
                            <p class="small text-muted mt-1 mb-0">{{ $photo->description }}</p>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Map -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Lokasi di Peta</h5>
                </div>
                <div class="card-body">
                    <div id="mapContainer">
                        <div class="text-center text-muted">
                            <i class="fas fa-map fa-3x mb-2"></i><br>
                            <p>Peta tidak tersedia<br>
                            <small>Koordinat: {{ $halte->latitude }}, {{ $halte->longitude }}</small></p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="https://maps.google.com/?q={{ $halte->latitude }},{{ $halte->longitude }}"
                           target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Singkat</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID Halte:</strong></td>
                            <td>{{ $halte->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $isRented ? 'bg-warning' : 'bg-success' }}">
                                    {{ $isRented ? 'Disewa' : 'Tersedia' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Foto:</strong></td>
                            <td>{{ $halte->photos->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>SIMBADA:</strong></td>
                            <td>
                                @if($halte->simbada_registered)
                                <span class="badge bg-success">Terdaftar</span>
                                @else
                                <span class="badge bg-secondary">Belum Terdaftar</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
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
                <p class="mt-2 text-muted" id="modalCaption"></p>
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

    photoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const src = button.getAttribute('data-bs-src');
        const caption = button.getAttribute('data-bs-caption');

        modalImage.src = src;
        modalCaption.textContent = caption;
    });
});
</script>
@endpush
