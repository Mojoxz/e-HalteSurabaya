@extends('layouts.user')

@section('title', 'Daftar Halte')
@section('page-title', 'Daftar Halte')

@section('content')
<div class="container-fluid">
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.haltes.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search"
                                   placeholder="Cari halte..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('user.haltes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Haltes Grid -->
    <div class="row">
        @forelse($haltes as $halte)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                @php
                    $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                    $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                        ? asset('storage/' . $primaryPhoto->photo_path)
                        : asset('images/halte-default.png');
                @endphp
                <img src="{{ $photoUrl }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $halte->name }}">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $halte->name }}</h5>

                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt"></i> {{ $halte->address }}
                        </small>
                    </div>

                    @if($halte->description)
                    <p class="card-text text-muted small">{{ Str::limit($halte->description, 100) }}</p>
                    @endif

                    @php
                        $isRented = $halte->isCurrentlyRented();
                    @endphp

                    <div class="mb-3">
                        <span class="badge {{ $isRented ? 'bg-warning' : 'bg-success' }} fs-6">
                            <i class="fas {{ $isRented ? 'fa-calendar' : 'fa-check-circle' }}"></i>
                            {{ $isRented ? 'Disewa' : 'Tersedia' }}
                        </span>
                    </div>

                    @if($isRented)
                    <div class="mb-2">
                        <small class="text-muted">
                            <strong>Disewa oleh:</strong> {{ $halte->rented_by }}<br>
                            <strong>Periode:</strong>
                            {{ $halte->rent_start_date ? $halte->rent_start_date->format('d/m/Y') : '-' }} -
                            {{ $halte->rent_end_date ? $halte->rent_end_date->format('d/m/Y') : 'Tidak terbatas' }}
                        </small>
                    </div>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('user.haltes.detail', $halte->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada halte yang ditemukan</h5>
                    <p class="text-muted">Coba ubah kriteria pencarian Anda</p>
                    <a href="{{ route('user.haltes.index') }}" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Reset Filter
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($haltes->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $haltes->links() }}
    </div>
    @endif
</div>
@endsection
