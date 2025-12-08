@extends('layouts.user')

@section('title', 'Peta Halte')
@section('page-title', 'Peta Halte')

@push('styles')
@vite(['resources/css/user/maps.css'])
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Map Section -->
        <div class="col-lg-8">
            <div class="map-container-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-map-marked-alt"></i> Peta Lokasi Halte
                        <span class="badge bg-primary ms-2">{{ count($haltesData) }} Halte</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Box inside Map -->
                    <div class="map-search-container">
                        <div class="map-search-box">
                            <div class="map-search-input-group">
                                <input type="text"
                                       id="mapSearchInput"
                                       class="map-search-input"
                                       placeholder="Cari nama halte atau alamat..."
                                       autocomplete="off">
                                <i class="fas fa-search map-search-icon"></i>
                            </div>
                            <div class="map-search-results" id="mapSearchResults"></div>
                        </div>
                    </div>

                    <div id="map"></div>

                    <div class="map-legend">
                        <h6>Keterangan:</h6>
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

            <!-- Statistics -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="stats-card success">
                        <h3>{{ $haltesData->where('rental_status', 'available')->count() }}</h3>
                        <p><i class="fas fa-check-circle"></i> Halte Tersedia</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card warning">
                        <h3>{{ $haltesData->where('rental_status', 'rented')->count() }}</h3>
                        <p><i class="fas fa-calendar"></i> Halte Disewa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Halte List Sidebar -->
        <div class="col-lg-4">
            <div class="halte-list-card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list"></i> Daftar Halte
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($haltesData as $halte)
                    <div class="halte-card {{ $halte['rental_status'] === 'available' ? 'status-available' : 'status-rented' }}"
                         onclick="showHalteInfo({{ json_encode($halte) }})">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6>{{ $halte['name'] }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt"></i> {{ Str::limit($halte['address'], 40) }}
                                </p>
                                <span class="badge {{ $halte['rental_status'] === 'available' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $halte['rental_status'] === 'available' ? 'Tersedia' : 'Disewa' }}
                                </span>
                            </div>
                            <div class="text-end">
                                @if($halte['primary_photo'])
                                <img src="{{ $halte['primary_photo'] }}"
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="{{ $halte['name'] }}">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if(count($haltesData) === 0)
                    <div class="empty-state">
                        <i class="fas fa-bus"></i>
                        <h6>Tidak ada halte tersedia</h6>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.haltesData = @json($haltesData);
</script>
@vite(['resources/js/user/maps.js'])
@endpush
