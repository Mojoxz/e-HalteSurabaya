@extends('layouts.app')

@section('title', 'Kelola Halte')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-list"></i> Kelola Halte</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.haltes.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Halte
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.haltes.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Halte</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Cari nama halte..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="simbada" class="form-label">SIMBADA</label>
                        <select name="simbada" id="simbada" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('simbada') == '1' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="0" {{ request('simbada') == '0' ? 'selected' : '' }}>Belum Terdaftar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Halte List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-bus"></i> Daftar Halte
                <span class="badge bg-primary">{{ $haltes->total() }} total</span>
            </h5>
            @if(request()->hasAny(['search', 'status', 'simbada']))
                <small class="text-muted">
                    <i class="fas fa-filter"></i> Filter aktif:
                    {{ $haltes->count() }} dari {{ $haltes->total() }} halte
                </small>
            @endif
        </div>
        <div class="card-body p-0">
            @if($haltes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">Foto</th>
                                <th>Nama Halte</th>
                                <th>Alamat</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">SIMBADA</th>
                                <th style="width: 150px;">Info Sewa</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($haltes as $halte)
                            <tr>
                                <td class="align-middle">
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
                                                 class="img-thumbnail rounded"
                                                 style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                 onclick="showPhotoModal('{{ $photoUrl }}', '{{ $halte->name }}')"
                                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="bg-light text-center d-none align-items-center justify-content-center rounded"
                                                 style="width: 60px; height: 60px; position: absolute; top: 0; left: 0;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            @if($halte->photos->count() > 1)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info"
                                                      style="font-size: 10px;">
                                                    {{ $halte->photos->count() }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light text-center d-flex align-items-center justify-content-center rounded border"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif

                                    {{-- Debug info (only in development) --}}
                                    @if(config('app.debug') && config('app.env') !== 'production')
                                        <small class="text-muted d-block mt-1" style="font-size: 10px;">
                                            {{ $halte->photos->count() }} foto
                                            @if($primaryPhoto) | Primary: ✓ @endif
                                        </small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <strong class="text-primary">{{ $halte->name }}</strong>
                                        @if($halte->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($halte->description, 60) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($halte->address)
                                        <small class="text-dark">{{ Str::limit($halte->address, 50) }}</small>
                                        <br>
                                    @endif
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ number_format($halte->latitude, 4) }}, {{ number_format($halte->longitude, 4) }}
                                    </small>
                                </td>
                                <td class="align-middle">
                                    @if($halte->isCurrentlyRented())
                                        <span class="badge bg-danger d-block mb-1">
                                            <i class="fas fa-clock"></i> Disewa
                                        </span>
                                    @else
                                        <span class="badge bg-success d-block mb-1">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($halte->simbada_registered)
                                        <span class="badge bg-info d-block mb-1">
                                            <i class="fas fa-check"></i> Terdaftar
                                        </span>
                                        @if($halte->simbada_number)
                                            <small class="text-muted d-block">{{ $halte->simbada_number }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($halte->isCurrentlyRented())
                                        <small class="text-dark">
                                            <strong>{{ $halte->rented_by }}</strong>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            {{ $halte->rent_start_date->format('d/m/Y') }} -
                                            {{ $halte->rent_end_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            <i class="fas fa-minus-circle"></i> Tidak disewa
                                        </small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
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
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Menampilkan {{ $haltes->firstItem() }} sampai {{ $haltes->lastItem() }}
                                dari {{ $haltes->total() }} halte
                            </div>
                            <div>
                                {{ $haltes->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    @if(request()->hasAny(['search', 'status', 'simbada']))
                        <!-- No results found -->
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada halte yang ditemukan</h5>
                        <p class="text-muted">Coba ubah kriteria pencarian atau filter Anda.</p>
                        <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times"></i> Reset Filter
                        </a>
                    @else
                        <!-- No data at all -->
                        <i class="fas fa-bus fa-3x text-muted mb-3"></i>
                        <h5>Belum ada data halte</h5>
                        <p class="text-muted">Klik tombol "Tambah Halte" untuk mulai menambahkan data halte.</p>
                        <a href="{{ route('admin.haltes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Halte Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Foto Halte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Apakah Anda yakin ingin menghapus halte:</p>
                <strong id="deleteHalteName" class="text-danger"></strong>
                <p class="mt-2 text-muted small">
                    <i class="fas fa-warning"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua foto dan data terkait akan ikut terhapus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Hapus Halte
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

// Search form enhancement
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.table th {
    font-weight: 600;
    font-size: 0.875rem;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.img-thumbnail {
    transition: transform 0.2s ease-in-out;
}

.img-thumbnail:hover {
    transform: scale(1.1);
    cursor: pointer;
}

.badge {
    font-size: 0.75rem;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }

    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
    }

    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
