{{-- resources/views/admin/rentals/index.blade.php - IMPROVED VERSION --}}
@extends('layouts.admin')

@section('title', 'Riwayat Sewa Halte')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb Navigation --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-tachometer-alt me-1"></i>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-history me-1"></i>
                Riwayat Sewa
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-3" title="Kembali ke Dashboard">
                <i class="fas fa-arrow-left me-1"></i>
                Dashboard
            </a>
            <div>
                <h2 class="h3 mb-1 text-gray-800">
                    <i class="fas fa-history text-primary me-2"></i>
                    Riwayat Sewa Halte
                </h2>
                <p class="text-muted mb-0">Kelola dan pantau riwayat penyewaan halte</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line me-1"></i>
                Lihat Laporan
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penyewaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $histories->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($histories->sum('rental_cost'), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sedang Disewa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $histories->where('rent_end_date', '>=', now())->where('rent_start_date', '<=', now())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                ID Halte
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $histories->pluck('halte_id')->unique()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                Filter Data
            </h6>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterCollapse" aria-expanded="false">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.rentals.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="halte_id" class="form-label">Halte</label>
                        <select class="form-select" id="halte_id" name="halte_id">
                            <option value="">Pilih Halte</option>
                            @foreach($haltes as $halte)
                                <option value="{{ $halte->id }}" {{ request('halte_id') == $halte->id ? 'selected' : '' }}>
                                    {{ $halte->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rented_by" class="form-label">Penyewa</label>
                        <input type="text" class="form-control" id="rented_by" name="rented_by"
                               placeholder="Nama penyewa" value="{{ request('rented_by') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Data Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>
                Data Riwayat Sewa
            </h6>
        </div>
        <div class="card-body">
            @if($histories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Halte</th>
                                <th width="15%">Penyewa</th>
                                <th width="12%">Tanggal Mulai</th>
                                <th width="12%">Tanggal Selesai</th>
                                <th width="10%">Status</th>
                                <th width="12%">Biaya Sewa</th>
                                <th width="10%">Dibuat Oleh</th>
                                <th width="4%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $index => $history)
                                <tr>
                                    <td class="text-center">
                                        {{ $histories->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($history->halte->photos->where('is_primary', true)->first())
                                                <img src="{{ asset('storage/' . $history->halte->photos->where('is_primary', true)->first()->photo_path) }}"
                                                     alt="{{ $history->halte->name }}"
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $history->halte->name }}</strong>
                                                @if($history->halte->address)
                                                    <br><small class="text-muted">{{ Str::limit($history->halte->address, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $history->rented_by }}</strong>
                                        @if($history->notes)
                                            <br><small class="text-muted" title="{{ $history->notes }}">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                {{ Str::limit($history->notes, 30) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-nowrap">
                                            {{ $history->rent_start_date->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $history->rent_start_date->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-nowrap">
                                            {{ $history->rent_end_date->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $history->rent_end_date->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $now = now();
                                            $isActive = $now->between($history->rent_start_date, $history->rent_end_date);
                                            $isUpcoming = $now->isBefore($history->rent_start_date);
                                            $isExpired = $now->isAfter($history->rent_end_date);
                                        @endphp

                                        @if($isActive)
                                            <span class="badge bg-success">
                                                <i class="fas fa-play me-1"></i>
                                                Aktif
                                            </span>
                                        @elseif($isUpcoming)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>
                                                Akan Datang
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-check me-1"></i>
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            Rp {{ number_format($history->rental_cost, 0, ',', '.') }}
                                        </strong>
                                        @if($history->rental_cost > 0)
                                            <br>
                                            <small class="text-muted">
                                                {{ $history->rent_start_date->diffInDays($history->rent_end_date) }} hari
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->creator)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                                     style="width: 30px; height: 30px; font-size: 12px;">
                                                    {{ strtoupper(substr($history->creator->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <small>{{ $history->creator->name }}</small>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $history->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.haltes.show', $history->halte_id) }}">
                                                        <i class="fas fa-eye me-1"></i>
                                                        Lihat Halte
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.haltes.edit', $history->halte_id) }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Edit Halte
                                                    </a>
                                                </li>
                                                @if($history->notes)
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <span class="dropdown-item-text">
                                                            <strong>Catatan:</strong><br>
                                                            <small>{{ $history->notes }}</small>
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-muted mb-0">
                            Menampilkan {{ $histories->firstItem() }} sampai {{ $histories->lastItem() }}
                            dari {{ $histories->total() }} data
                        </p>
                    </div>
                    <div>
                        {{ $histories->links() }}
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Belum Ada Data Riwayat Sewa</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['start_date', 'end_date', 'halte_id', 'rented_by']))
                            Tidak ada data yang sesuai dengan filter yang dipilih.
                            <br>
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-primary mt-2">
                                <i class="fas fa-times me-1"></i>
                                Reset Filter
                            </a>
                        @else
                            Belum ada halte yang disewakan. Mulai dengan menambah halte dan mengatur status sewa.
                            <br>
                            <a href="{{ route('admin.haltes.index') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>
                                Kelola Halte
                            </a>
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table th {
    background-color: #f8f9fc;
    font-weight: 600;
    font-size: 0.85rem;
    color: #5a5c69;
    border-color: #e3e6f0;
}

.table td {
    font-size: 0.875rem;
    color: #5a5c69;
    border-color: #e3e6f0;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

/* Breadcrumb Styling */
.breadcrumb {
    background-color: #f8f9fc;
    border: 1px solid #e3e6f0;
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

.breadcrumb-item a {
    color: #5a5c69;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #3a3b45;
}

.breadcrumb-item.active {
    color: #858796;
}

/* Gap utilities for older Bootstrap versions */
.gap-2 {
    gap: 0.5rem;
}

.d-flex.gap-2 > * + * {
    margin-left: 0.5rem;
}

/* Button enhancements */
.btn {
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

{{-- Custom Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-collapse filter on mobile
    if (window.innerWidth < 768) {
        const filterCollapse = document.getElementById('filterCollapse');
        if (filterCollapse && !filterCollapse.classList.contains('show')) {
            filterCollapse.style.display = 'none';
        }
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add search functionality to table
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
