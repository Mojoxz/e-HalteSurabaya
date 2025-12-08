{{-- resources/views/admin/rentals/index.blade.php - CLEAN VERSION --}}
@extends('layouts.admin')

@section('title', 'Riwayat Sewa Halte')

{{-- Include CSS --}}
@push('styles')
    @vite(['resources/css/admin/rental/index.css'])
@endpush

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

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    {{-- Filter Section - AUTO SEARCH --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                Filter Data
                <span id="filterIndicator" class="badge bg-info ms-2" style="display: none;">
                    <i class="fas fa-sync-alt fa-spin"></i> Mencari...
                </span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                @if(request()->hasAny(['start_date', 'end_date', 'halte_id', 'rented_by']))
                    <span class="badge bg-success">
                        <i class="fas fa-filter me-1"></i>
                        Filter Aktif
                    </span>
                @endif
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="true">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.rentals.index') }}" id="autoFilterForm" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" class="form-control auto-search" id="start_date" name="start_date"
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">
                            <i class="fas fa-calendar-check me-1"></i>
                            Tanggal Selesai
                        </label>
                        <input type="date" class="form-control auto-search" id="end_date" name="end_date"
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="halte_id" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Halte
                        </label>
                        <select class="form-select auto-search" id="halte_id" name="halte_id">
                            <option value="">Semua Halte</option>
                            @foreach($haltes as $halte)
                                <option value="{{ $halte->id }}" {{ request('halte_id') == $halte->id ? 'selected' : '' }}>
                                    {{ $halte->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rented_by" class="form-label">
                            <i class="fas fa-user me-1"></i>
                            Penyewa
                        </label>
                        <input type="text" class="form-control auto-search" id="rented_by" name="rented_by"
                               placeholder="Cari nama penyewa..." value="{{ request('rented_by') }}">
                        <small class="text-muted">Pencarian otomatis saat mengetik</small>
                    </div>
                    <div class="col-12">
                        @if(request()->hasAny(['start_date', 'end_date', 'halte_id', 'rented_by']))
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Reset Filter
                            </a>
                        @endif
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
                                <th width="15%">Halte</th>
                                <th width="12%">Penyewa</th>
                                <th width="15%">Periode Sewa</th>
                                <th width="10%">Status</th>
                                <th width="10%">Biaya Sewa</th>
                                <th width="8%">Dokumen</th>
                                <th width="12%">Catatan</th>
                                <th width="10%">Dibuat Oleh</th>
                                <th width="5%">Aksi</th>
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
                                    </td>
                                    <td>
                                        <small class="d-block">
                                            <i class="fas fa-calendar-alt text-success me-1"></i>
                                            <strong>Mulai:</strong> {{ $history->rent_start_date->format('d M Y') }}
                                        </small>
                                        <small class="d-block">
                                            <i class="fas fa-calendar-times text-danger me-1"></i>
                                            <strong>Selesai:</strong> {{ $history->rent_end_date->format('d M Y') }}
                                        </small>
                                        <small class="d-block text-muted">
                                            ({{ $history->rent_start_date->diffInDays($history->rent_end_date) }} hari)
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
                                    </td>
                                    {{-- DOCUMENTS COLUMN --}}
                                    <td class="text-center">
                                        @if($history->hasDocuments())
                                            <button type="button"
                                                    class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#documentsModal{{ $history->id }}">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                {{ $history->documents->count() }}
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->notes)
                                            <small class="text-muted" title="{{ $history->notes }}">
                                                {{ Str::limit($history->notes, 30) }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
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

{{-- DOCUMENTS MODALS --}}
@foreach($histories as $history)
    @if($history->hasDocuments())
        <div class="modal fade" id="documentsModal{{ $history->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-pdf me-2"></i>
                            Dokumen Penyewaan - {{ $history->halte->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Penyewa:</strong> {{ $history->rented_by }}<br>
                            <strong>Periode:</strong>
                            {{ $history->rent_start_date->format('d M Y') }} -
                            {{ $history->rent_end_date->format('d M Y') }}
                        </div>
                        <hr>
                        <div class="row g-3">
                            @foreach($history->documents as $document)
                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="{{ $document->icon_class }} fa-2x me-2"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-truncate" title="{{ $document->document_name }}">
                                                        {{ $document->document_name }}
                                                    </h6>
                                                    <small class="text-muted d-block">
                                                        {{ $document->formatted_file_size }}
                                                    </small>
                                                    @if($document->description)
                                                        <small class="text-muted d-block">
                                                            {{ $document->description }}
                                                        </small>
                                                    @endif
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $document->uploader->name ?? 'System' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="btn-group w-100">
                                                @if($document->isPdf())
                                                    <a href="{{ route('admin.rentals.documents.view', $document->id) }}"
                                                       target="_blank"
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-info btn-sm"
                                                            onclick="showDocumentModal('{{ $document->document_url }}', '{{ $document->document_name }}')">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.rentals.documents.download', $document->id) }}"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- Document Modal for Images --}}
<div class="modal fade" id="documentImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentImageModalLabel">Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalDocumentImage" class="img-fluid rounded" alt="Dokumen">
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Include JavaScript --}}
@push('scripts')
    @vite(['resources/js/admin/rental/index.js'])
@endpush
