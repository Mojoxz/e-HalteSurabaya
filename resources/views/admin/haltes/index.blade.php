@extends('layouts.app')

@section('title', 'Kelola Halte')

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
            <h6 class="card-title mb-0 text-dark">
                <i class="fas fa-filter me-2"></i>Filter & Pencarian
            </h6>
        </div>
        <div class="card-body bg-white">
            <form method="GET" action="{{ route('admin.haltes.index') }}" id="filterForm">
                <!-- FIXED: Preserve sort parameters in form -->
                <input type="hidden" name="sort" value="{{ $sortField }}">
                <input type="hidden" name="direction" value="{{ $sortDirection }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-semibold">
                            <i class="fas fa-search me-1"></i>Cari Halte
                        </label>
                        <input type="text" name="search" id="search" class="form-control form-control-lg border-2"
                               placeholder="Cari nama halte..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-info-circle me-1"></i>Status
                        </label>
                        <select name="status" id="status" class="form-select form-select-lg border-2">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="simbada" class="form-label fw-semibold">
                            <i class="fas fa-database me-1"></i>SIMBADA
                        </label>
                        <select name="simbada" id="simbada" class="form-select form-select-lg border-2">
                            <option value="">Semua</option>
                            <option value="1" {{ request('simbada') == '1' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="0" {{ request('simbada') == '0' ? 'selected' : '' }}>Belum Terdaftar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.haltes.index', ['reset' => '1']) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
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
                                <!-- FIXED: Sortable columns -->
                                <th class="sortable-header" data-sort="name">
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
                                <th style="width: 100px;" class="text-center sortable-header" data-sort="status">
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
                                        // Get primary photo or first available photo
                                        $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                                        $firstPhoto = $halte->photos->first();
                                        $photoUrl = null;
                                        $photoFound = false;

                                        // Check primary photo first
                                        if ($primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))) {
                                            $photoUrl = asset('storage/' . $primaryPhoto->photo_path);
                                            $photoFound = true;
                                        }
                                        // Fallback to first photo
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

                                    <!-- Hidden delete form -->
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

                <!-- Pagination -->
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
                                        {{-- Previous Page Link --}}
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

                                        {{-- Pagination Elements --}}
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

                                        {{-- Next Page Link --}}
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
                        <!-- No results found -->
                        <div class="empty-state">
                            <i class="fas fa-search fa-4x text-muted mb-4 opacity-50"></i>
                            <h4 class="text-muted fw-bold">Tidak ada halte yang ditemukan</h4>
                            <p class="text-muted mb-4">Coba ubah kriteria pencarian atau filter Anda.</p>
                            <a href="{{ route('admin.haltes.index', ['reset' => '1']) }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-times me-2"></i> Reset Filter
                            </a>
                        </div>
                    @else
                        <!-- No data at all -->
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt fa-3x text-danger opacity-50"></i>
                </div>
                <p class="mb-2 text-center">Apakah Anda yakin ingin menghapus halte:</p>
                <div class="text-center mb-3">
                    <strong id="deleteHalteName" class="text-danger fs-5"></strong>
                </div>
                <div class="alert alert-warning border-0">
                    <i class="fas fa-warning me-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan. Semua foto dan data terkait akan ikut terhapus.
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i> Hapus Halte
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// FIXED: Auto sorting functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle sortable headers
    const sortableHeaders = document.querySelectorAll('.sortable-header');

    sortableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const sortField = this.dataset.sort;
            const currentSort = '{{ $sortField }}';
            const currentDirection = '{{ $sortDirection }}';

            let newDirection = 'asc';
            if (sortField === currentSort && currentDirection === 'asc') {
                newDirection = 'desc';
            }

            // Build URL with current parameters
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortField);
            url.searchParams.set('direction', newDirection);

            // Add loading state
            this.classList.add('loading');

            // Navigate to new URL
            window.location.href = url.toString();
        });
    });

    // Add hover effect to sortable headers
    sortableHeaders.forEach(header => {
        header.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(255,255,255,0.1)';
        });

        header.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});

// Photo modal functionality
function showPhotoModal(photoUrl, halteName) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('modalPhoto').alt = halteName;
    document.getElementById('photoModalLabel').textContent = 'Foto ' + halteName;

    var photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
    photoModal.show();
}

// Delete confirmation
let deleteHalteId = null;

function confirmDelete(halteId, halteName) {
    deleteHalteId = halteId;
    document.getElementById('deleteHalteName').textContent = halteName;

    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteHalteId) {
        // Add loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
        this.disabled = true;

        document.getElementById('delete-form-' + deleteHalteId).submit();
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Enhanced image error handling
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('img[onerror]').forEach(function(img) {
        img.addEventListener('error', function() {
            console.log('Failed to load image:', this.src);
        });
    });
});

// Search form enhancement with auto-submit on filter change
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const simbadaSelect = document.getElementById('simbada');
    const filterForm = document.getElementById('filterForm');

    // Auto-submit on filter change
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    if (simbadaSelect) {
        simbadaSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    // Search on Enter key
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterForm.submit();
            }
        });
    }

    // Clear search on Escape key
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
            }
        });
    }
});

// Add loading state for pagination and sorting
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to pagination links
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (!this.closest('.page-item').classList.contains('disabled') &&
                !this.closest('.page-item').classList.contains('active')) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
        });
    });
});

// Form submission loading states
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mencari...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Page Header Styling */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

/* Card Enhancements */
.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
}

/* Modern Table Styling */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table th {
    font-weight: 600;
    font-size: 0.875rem;
    padding: 1rem 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.modern-table td {
    padding: 1rem 0.75rem;
    border-top: 1px solid #e9ecef;
    vertical-align: middle;
}

.table-row-hover {
    transition: all 0.3s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* FIXED: Sortable header styling */
.sortable-header {
    transition: all 0.2s ease;
    user-select: none;
    position: relative;
}

.sortable-header:hover {
    background-color: rgba(255,255,255,0.1) !important;
    color: #fff !important;
}

.sort-icons {
    margin-left: 8px;
    display: inline-block;
    font-size: 0.8rem;
}

.sort-icons i {
    transition: all 0.2s ease;
}

.sortable-header:hover .sort-icons .fa-sort {
    color: #ffc107 !important;
    opacity: 1 !important;
}

/* Image Hover Effects */
.hover-zoom {
    transition: all 0.3s ease;
}

.hover-zoom:hover {
    transform: scale(1.15);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 10;
    position: relative;
}

/* Badge Improvements */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    letter-spacing: 0.3px;
}

.fs-7 {
    font-size: 0.875rem !important;
}

/* Button Group Enhancements */
.btn-group .btn {
    border-radius: 6px;
    margin-right: 4px;
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Form Control Improvements */
.form-control, .form-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    transform: translateY(-1px);
}

/* Empty State Styling */
.empty-state {
    padding: 3rem 2rem;
}

.empty-state i {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Alert Improvements */
.alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.5rem;
}

/* Modal Enhancements */
.modal-content {
    border-radius: 12px;
}

.modal-header {
    border-radius: 12px 12px 0 0;
    padding: 1.5rem;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 1.5rem;
    border-radius: 0 0 12px 12px;
}

/* Pagination Improvements */
.pagination-wrapper {
    display: flex;
    align-items: center;
}

.pagination-sm .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    color: #6c757d;
    transition: all 0.3s ease;
    margin: 0 2px;
}

.pagination-sm .page-item:first-child .page-link,
.pagination-sm .page-item:last-child .page-link {
    border-radius: 6px;
}

.pagination-sm .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination-sm .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(13,110,253,0.25);
}

.pagination-sm .page-item.disabled .page-link {
    color: #adb5bd;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    opacity: 0.6;
}

.pagination-sm .page-link i {
    font-size: 0.75rem;
}

/* Pagination responsive */
@media (max-width: 576px) {
    .pagination-wrapper {
        width: 100%;
        justify-content: center;
        margin-top: 1rem;
    }

    .pagination-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        margin: 0 1px;
    }

    .card-footer .d-flex {
        flex-direction: column;
    }

    .text-muted.small {
        text-align: center;
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
        text-align: center;
    }

    .page-header .btn-toolbar {
        width: 100%;
        justify-content: center;
        margin-top: 1rem;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        margin-bottom: 0.5rem;
        margin-right: 0;
        width: 100%;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .modern-table th,
    .modern-table td {
        padding: 0.75rem 0.5rem;
    }

    .card-header h5 {
        font-size: 1rem;
    }

    .modal-dialog {
        margin: 1rem;
    }

    /* Hide sort icons on mobile for space */
    .sort-icons {
        display: none;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 1rem;
    }

    .page-header {
        padding: 1rem;
    }

    .card-body {
        padding: 1rem;
    }

    /* Stack table columns on very small screens */
    .table-responsive {
        font-size: 0.75rem;
    }

    .modern-table th,
    .modern-table td {
        padding: 0.5rem 0.25rem;
    }

    /* Hide less important columns on mobile */
    .modern-table th:nth-child(3),
    .modern-table td:nth-child(3),
    .modern-table th:nth-child(5),
    .modern-table td:nth-child(5) {
        display: none;
    }
}

/* Print Styles */
@media print {
    .btn, .btn-group, .modal, .alert, .page-header .btn-toolbar {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    .table th {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }

    .sortable-header {
        cursor: default !important;
    }

    .sort-icons {
        display: none !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-light {
        background-color: #343a40 !important;
        color: #ffffff !important;
    }

    .text-muted {
        color: #adb5bd !important;
    }

    .border {
        border-color: #495057 !important;
    }

    .table-row-hover:hover {
        background-color: #495057 !important;
    }
}

/* Loading Animation for sorting */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid #ccc;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 10;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Smooth transitions */
* {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
                border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Additional enhancements for better UX */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
    transform: translateY(-1px);
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(13,110,253,0.25);
}

/* Status indicators */
.badge.bg-danger {
    background-color: #dc3545 !important;
    animation: pulse-red 2s infinite;
}

.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-info {
    background-color: #0dcaf0 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
}

@keyframes pulse-red {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Sort direction indicators */
.fa-sort-up {
    color: #ffc107 !important;
}

.fa-sort-down {
    color: #ffc107 !important;
}

.fa-sort {
    opacity: 0.5;
}

/* Tooltip for sorting */
.sortable-header[title] {
    position: relative;
}

.sortable-header::before {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #000;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
    z-index: 1000;
    white-space: nowrap;
}

.sortable-header:hover::before {
    opacity: 0.9;
}
</style>
@endpush
