@extends('layouts.admin')

@section('title', 'Kelola Halte')

{{-- Import CSS via Vite --}}
@vite(['resources/css/admin/haltes-index.css'])

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
        <div>
            <h1 class="h2 text-primary fw-bold mb-0">
                <i class="fas fa-bus me-2"></i> Kelola Halte
            </h1>
            <p class="text-muted mb-0">Manajemen data halte bus transportasi</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.haltes.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Halte
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light border-bottom-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-dark">
                    <i class="fas fa-filter me-2"></i>Filter & Pencarian
                </h6>
                @if(request()->hasAny(['search', 'status', 'simbada']))
                    <a href="{{ route('admin.haltes.index', ['reset' => '1']) }}"
                       class="btn btn-sm btn-outline-secondary"
                       title="Hapus semua filter">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body bg-white">
            <form method="GET" action="{{ route('admin.haltes.index') }}" id="filterForm">
                <input type="hidden" name="sort" value="{{ $sortField }}">
                <input type="hidden" name="direction" value="{{ $sortDirection }}">

                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">
                            <i class="fas fa-search me-1"></i>Cari Halte
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-2">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   class="form-control border-2"
                                   placeholder="Ketik nama halte... (auto search)"
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            @if(request('search'))
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="document.getElementById('search').value=''; document.getElementById('search').dispatchEvent(new Event('input'));"
                                        title="Hapus pencarian">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-info-circle me-1"></i>Status
                        </label>
                        <select name="status"
                                id="status"
                                class="form-select form-select-lg border-2">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                                Tersedia
                            </option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>
                                Disewa
                            </option>
                        </select>
                    </div>

                    <!-- SIMBADA Filter -->
                    <div class="col-md-3">
                        <label for="simbada" class="form-label fw-semibold">
                            <i class="fas fa-database me-1"></i>SIMBADA
                        </label>
                        <select name="simbada"
                                id="simbada"
                                class="form-select form-select-lg border-2">
                            <option value="">Semua</option>
                            <option value="1" {{ request('simbada') == '1' ? 'selected' : '' }}>
                                Terdaftar
                            </option>
                            <option value="0" {{ request('simbada') == '0' ? 'selected' : '' }}>
                                Belum Terdaftar
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Indicator -->
                @if(request()->hasAny(['search', 'status', 'simbada']))
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="filter-badges">
                                <span class="text-muted me-2">
                                    <i class="fas fa-filter me-1"></i>Filter aktif:
                                </span>

                                @if(request('search'))
                                    <span class="badge bg-primary me-2">
                                        <i class="fas fa-search me-1"></i>
                                        Pencarian: "{{ request('search') }}"
                                    </span>
                                @endif

                                @if(request('status'))
                                    <span class="badge bg-info me-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Status: {{ request('status') == 'available' ? 'Tersedia' : 'Disewa' }}
                                    </span>
                                @endif

                                @if(request('simbada') !== null && request('simbada') !== '')
                                    <span class="badge bg-warning text-dark me-2">
                                        <i class="fas fa-database me-1"></i>
                                        SIMBADA: {{ request('simbada') == '1' ? 'Terdaftar' : 'Belum Terdaftar' }}
                                    </span>
                                @endif
                            </div>

                            <div>
                                <span class="text-muted me-2">
                                    Menampilkan {{ $haltes->count() }} dari {{ $haltes->total() }} halte
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Halte List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fas fa-list me-2"></i> Daftar Halte
                <span class="badge bg-light text-primary ms-2">{{ $haltes->total() }} total</span>
            </h5>
            @if(request()->hasAny(['search', 'status', 'simbada']))
                <small class="text-light opacity-75">
                    <i class="fas fa-filter me-1"></i> Filter aktif:
                    {{ $haltes->count() }} dari {{ $haltes->total() }} halte
                </small>
            @endif
        </div>
        <div class="card-body p-0">
            @if($haltes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 modern-table">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;" class="text-center">Foto</th>
                                <th class="sortable-header"
                                    data-sort="name"
                                    data-current-sort="{{ $sortField }}"
                                    data-current-direction="{{ $sortDirection }}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Nama Halte</span>
                                        <div class="sort-icons">
                                            @if($sortField === 'name')
                                                @if($sortDirection === 'asc')
                                                    <i class="fas fa-sort-up text-warning"></i>
                                                @else
                                                    <i class="fas fa-sort-down text-warning"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort text-muted opacity-50"></i>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <th>Alamat</th>
                                <th style="width: 100px;" class="text-center sortable-header"
                                    data-sort="status"
                                    data-current-sort="{{ $sortField }}"
                                    data-current-direction="{{ $sortDirection }}">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span>Status</span>
                                        <div class="sort-icons ms-2">
                                            @if($sortField === 'status')
                                                @if($sortDirection === 'asc')
                                                    <i class="fas fa-sort-up text-warning"></i>
                                                @else
                                                    <i class="fas fa-sort-down text-warning"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort text-muted opacity-50"></i>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <th style="width: 120px;" class="text-center">SIMBADA</th>
                                <th style="width: 150px;">Info Sewa</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($haltes as $halte)
                            <tr class="table-row-hover">
                                <td class="align-middle text-center">
                                    @php
                                        $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                                        $firstPhoto = $halte->photos->first();
                                        $photoUrl = null;
                                        $photoFound = false;

                                        if ($primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))) {
                                            $photoUrl = asset('storage/' . $primaryPhoto->photo_path);
                                            $photoFound = true;
                                        }
                                        elseif ($firstPhoto && file_exists(storage_path('app/public/' . $firstPhoto->photo_path))) {
                                            $photoUrl = asset('storage/' . $firstPhoto->photo_path);
                                            $photoFound = true;
                                        }
                                    @endphp

                                    @if($photoFound && $photoUrl)
                                        <div class="position-relative">
                                            <img src="{{ $photoUrl }}"
                                                 alt="{{ $halte->name }}"
                                                 class="img-thumbnail rounded-3 shadow-sm hover-zoom"
                                                 style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                 onclick="showPhotoModal('{{ $photoUrl }}', '{{ $halte->name }}')"
                                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="bg-light text-center d-none align-items-center justify-content-center rounded-3 shadow-sm"
                                                 style="width: 60px; height: 60px; position: absolute; top: 0; left: 0;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            @if($halte->photos->count() > 1)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info shadow-sm"
                                                      style="font-size: 10px;">
                                                    {{ $halte->photos->count() }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light text-center d-flex align-items-center justify-content-center rounded-3 border shadow-sm"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <strong class="text-primary fs-6">{{ $halte->name }}</strong>
                                        @if($halte->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($halte->description, 60) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($halte->address)
                                        <div class="mb-1">
                                            <small class="text-dark fw-medium">{{ Str::limit($halte->address, 50) }}</small>
                                        </div>
                                    @endif
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                        {{ number_format($halte->latitude, 4) }}, {{ number_format($halte->longitude, 4) }}
                                    </small>
                                </td>
                                <td class="align-middle text-center">
                                    @if($halte->isCurrentlyRented())
                                        <span class="badge bg-danger fs-7 px-3 py-2">
                                            <i class="fas fa-clock me-1"></i> Disewa
                                        </span>
                                    @else
                                        <span class="badge bg-success fs-7 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    @if($halte->simbada_registered)
                                        <span class="badge bg-info fs-7 px-3 py-2 mb-1 d-block">
                                            <i class="fas fa-check me-1"></i> Terdaftar
                                        </span>
                                        @if($halte->simbada_number)
                                            <small class="text-muted d-block">{{ $halte->simbada_number }}</small>
                                        @endif
                                        {{-- DOCUMENT INDICATOR - NEW --}}
                                        @if($halte->hasSimbadaDocuments())
                                            <small class="d-block mt-1">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                                <span class="text-muted">{{ $halte->simbadaDocuments->count() }} dok</span>
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark fs-7 px-3 py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($halte->isCurrentlyRented())
                                        <div class="mb-1">
                                            <small class="text-dark fw-semibold">
                                                {{ $halte->rented_by }}
                                            </small>
                                        </div>
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="fas fa-calendar me-1 text-primary"></i>
                                            <span>{{ $halte->rent_start_date->format('d/m/Y') }} - {{ $halte->rent_end_date->format('d/m/Y') }}</span>
                                        </small>
                                        {{-- RENTAL DOCUMENT INDICATOR - NEW --}}
                                        @php
                                            $currentRental = $halte->rentalHistories->first();
                                        @endphp
                                        @if($currentRental && $currentRental->hasDocuments())
                                            <small class="d-block mt-1">
                                                <i class="fas fa-file-contract text-info"></i>
                                                <span class="text-muted">{{ $currentRental->documents->count() }} dok</span>
                                            </small>
                                        @endif
                                    @else
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="fas fa-minus-circle me-1"></i> Tidak disewa
                                        </small>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a href="{{ route('admin.haltes.show', $halte->id) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.haltes.edit', $halte->id) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus"
                                                onclick="confirmDelete({{ $halte->id }}, '{{ $halte->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $halte->id }}"
                                          action="{{ route('admin.haltes.destroy', $halte->id) }}"
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($haltes->hasPages())
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted small mb-2 mb-md-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan {{ $haltes->firstItem() }} sampai {{ $haltes->lastItem() }}
                                dari {{ $haltes->total() }} halte
                                @if($sortField !== 'name' || $sortDirection !== 'asc')
                                    <span class="text-primary ms-2">
                                        (Diurutkan berdasarkan {{ $sortField === 'name' ? 'Nama' : ($sortField === 'status' ? 'Status' : $sortField) }}
                                        {{ $sortDirection === 'asc' ? 'A-Z' : 'Z-A' }})
                                    </span>
                                @endif
                            </div>
                            <div class="pagination-wrapper">
                                <nav aria-label="Halte pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        @if ($haltes->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $haltes->previousPageUrl() }}" rel="prev">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        @foreach ($haltes->getUrlRange(1, $haltes->lastPage()) as $page => $url)
                                            @if ($page == $haltes->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        @if ($haltes->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $haltes->nextPageUrl() }}" rel="next">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    @if(request()->hasAny(['search', 'status', 'simbada']))
                        <div class="empty-state">
                            <i class="fas fa-search fa-4x text-muted mb-4 opacity-50"></i>
                            <h4 class="text-muted fw-bold">Tidak ada halte yang ditemukan</h4>
                            <p class="text-muted mb-4">Coba ubah kriteria pencarian atau filter Anda.</p>
                            <a href="{{ route('admin.haltes.index', ['reset' => '1']) }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-times me-2"></i> Hapus Semua Filter
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-bus fa-4x text-muted mb-4 opacity-50"></i>
                            <h4 class="fw-bold">Belum ada data halte</h4>
                            <p class="text-muted mb-4">Klik tombol "Tambah Halte" untuk mulai menambahkan data halte.</p>
                            <a href="{{ route('admin.haltes.create') }}" class="btn btn-primary btn-lg shadow">
                                <i class="fas fa-plus me-2"></i> Tambah Halte Pertama
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="photoModalLabel">
                    <i class="fas fa-image me-2"></i>Foto Halte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalPhoto" src="" alt="" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Import JavaScript via Vite --}}
@push('scripts')
    @vite(['resources/js/admin/haltes-index.js'])
@endpush
