@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard User')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Halte</h5>
                            <h2 class="mb-0">{{ $totalHaltes }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-bus fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Halte Tersedia</h5>
                            <h2 class="mb-0">{{ $availableHaltes }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Halte Disewa</h5>
                            <h2 class="mb-0">{{ $rentedHaltes }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.haltes.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-list fa-2x d-block mb-2"></i>
                                <strong>Lihat Semua Halte</strong><br>
                                <small>Jelajahi daftar lengkap halte yang tersedia</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.map') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-map-marked-alt fa-2x d-block mb-2"></i>
                                <strong>Lihat Peta</strong><br>
                                <small>Temukan lokasi halte di peta interaktif</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-user-edit fa-2x d-block mb-2"></i>
                                <strong>Edit Profil</strong><br>
                                <small>Perbarui informasi akun Anda</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Haltes -->
    @if($recentHaltes->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Halte Terbaru</h5>
                    <a href="{{ route('user.haltes.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentHaltes as $halte)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                @php
                                    $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                                    $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                                        ? asset('storage/' . $primaryPhoto->photo_path)
                                        : asset('images/halte-default.png');
                                @endphp
                                <img src="{{ $photoUrl }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $halte->name }}">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $halte->name }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> {{ Str::limit($halte->address, 50) }}
                                        </small>
                                    </p>
                                    @php
                                        $isRented = $halte->isCurrentlyRented();
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $isRented ? 'bg-warning' : 'bg-success' }}">
                                            {{ $isRented ? 'Disewa' : 'Tersedia' }}
                                        </span>
                                        <a href="{{ route('user.haltes.detail', $halte->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
