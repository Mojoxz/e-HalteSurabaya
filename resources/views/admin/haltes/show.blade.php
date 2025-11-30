@extends('layouts.admin')

@section('title', 'Detail Halte - ' . $halte->name)

@section('page-title', 'Detail Halte')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.haltes.index') }}">Daftar Halte</a></li>
    <li class="breadcrumb-item active">{{ $halte->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.haltes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <div class="btn-group">
            <a href="{{ route('admin.haltes.edit', $halte->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $halte->id }})">
                <i class="fas fa-trash me-2"></i>Hapus
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Main Information Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Halte</h5>
                </div>
                <div class="card-body">
                    <h3 class="card-title mb-3">{{ $halte->name }}</h3>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if($halte->is_rented && $halte->rent_end_date >= now())
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-lock me-1"></i>Sedang Disewa
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                </span>
                            @endif

                            @if($halte->simbada_registered)
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-file-alt me-1"></i>Terdaftar SIMBADA
                                </span>
                            @endif
                        </div>

                        <!-- Status Keterangan Detail -->
                        <div class="alert alert-{{ $halte->is_rented && $halte->rent_end_date >= now() ? 'danger' : 'success' }} mt-3 mb-0" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @if($halte->is_rented && $halte->rent_end_date >= now())
                                        <i class="fas fa-exclamation-circle fa-2x"></i>
                                    @else
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    @if($halte->is_rented && $halte->rent_end_date >= now())
                                        <h6 class="alert-heading mb-1">Halte Sedang Disewa</h6>
                                        <p class="mb-0">
                                            Halte ini <strong>tidak tersedia</strong> untuk disewa karena sedang dalam masa penyewaan
                                            oleh <strong>{{ $halte->rented_by }}</strong>
                                            sampai dengan tanggal <strong>{{ \Carbon\Carbon::parse($halte->rent_end_date)->format('d M Y') }}</strong>
                                            @php
                                                $daysRemaining = now()->diffInDays($halte->rent_end_date, false);
                                            @endphp
                                            ({{ $daysRemaining }} hari lagi).
                                        </p>
                                    @else
                                        <h6 class="alert-heading mb-1">Halte Tersedia</h6>
                                        <p class="mb-0">
                                            Halte ini <strong>tersedia</strong> dan dapat disewa.
                                            @if($halte->rentalHistories->count() > 0)
                                                Halte ini sudah pernah disewa sebanyak <strong>{{ $halte->rentalHistories->count() }} kali</strong>.
                                            @else
                                                Halte ini belum pernah disewa sebelumnya.
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($halte->description)
                        <div class="mb-4">
                            <h6 class="text-muted">Deskripsi</h6>
                            <p class="text-secondary">{{ $halte->description }}</p>
                        </div>
                    @endif

                    <hr>

                    <!-- Details Grid -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <strong>Alamat:</strong>
                                <p class="ms-4 mb-0">{{ $halte->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <i class="fas fa-globe text-primary me-2"></i>
                                <strong>Koordinat:</strong>
                                <p class="ms-4 mb-0">
                                    <a href="https://www.google.com/maps?q={{ $halte->latitude }},{{ $halte->longitude }}"
                                       target="_blank" class="text-decoration-none">
                                        {{ $halte->latitude }}, {{ $halte->longitude }}
                                        <i class="fas fa-external-link-alt ms-1 small"></i>
                                    </a>
                                </p>
                            </div>
                        </div>

                        @if($halte->simbada_registered)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-hashtag text-info me-2"></i>
                                    <strong>Nomor SIMBADA:</strong>
                                    <p class="ms-4 mb-0">{{ $halte->simbada_number ?? '-' }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="detail-item">
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                <strong>Ditambahkan:</strong>
                                <p class="ms-4 mb-0">{{ $halte->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <i class="fas fa-calendar-check text-warning me-2"></i>
                                <strong>Terakhir Diupdate:</strong>
                                <p class="ms-4 mb-0">{{ $halte->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIMBADA DOCUMENTS SECTION - NEW --}}
            @if($halte->simbadaDocuments->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-pdf me-2"></i>
                        Dokumen SIMBADA ({{ $halte->simbadaDocuments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($halte->simbadaDocuments as $document)
                        <div class="col-md-6">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="{{ $document->icon_class }} fa-3x me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-truncate" title="{{ $document->document_name }}">
                                                {{ $document->document_name }}
                                            </h6>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-weight-hanging me-1"></i>{{ $document->formatted_file_size }}
                                            </small>
                                            @if($document->description)
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle me-1"></i>{{ $document->description }}
                                            </small>
                                            @endif
                                            <small class="text-muted d-block">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $document->uploader->name ?? 'System' }} •
                                                {{ $document->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group w-100">
                                        @if($document->isPdf())
                                        <a href="{{ route('admin.haltes.documents.view', $document->id) }}"
                                           target="_blank"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        @else
                                        <button type="button"
                                                class="btn btn-info btn-sm"
                                                onclick="showDocumentModal('{{ $document->document_url }}', '{{ $document->document_name }}')">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.haltes.documents.download', $document->id) }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Rental Information Card -->
            @if($halte->is_rented)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Informasi Sewa Aktif</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <strong>Disewa oleh:</strong>
                                    <p class="ms-4 mb-0">{{ $halte->rented_by }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-calendar-day text-success me-2"></i>
                                    <strong>Mulai Sewa:</strong>
                                    <p class="ms-4 mb-0">{{ \Carbon\Carbon::parse($halte->rent_start_date)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-calendar-times text-danger me-2"></i>
                                    <strong>Akhir Sewa:</strong>
                                    <p class="ms-4 mb-0">{{ \Carbon\Carbon::parse($halte->rent_end_date)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <strong>Sisa Waktu:</strong>
                                    <p class="ms-4 mb-0">
                                        @php
                                            $daysRemaining = now()->diffInDays($halte->rent_end_date, false);
                                        @endphp
                                        @if($daysRemaining > 0)
                                            <span class="badge bg-warning text-dark">{{ $daysRemaining }} hari lagi</span>
                                        @else
                                            <span class="badge bg-secondary">Sudah berakhir</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- RENTAL DOCUMENTS SECTION - NEW --}}
                        @php
                            $currentRental = $halte->rentalHistories->first();
                        @endphp
                        @if($currentRental && $currentRental->documents->count() > 0)
                        <hr>
                        <h6 class="mb-3">
                            <i class="fas fa-file-contract text-info me-2"></i>
                            Dokumen Penyewaan ({{ $currentRental->documents->count() }})
                        </h6>
                        <div class="row g-3">
                            @foreach($currentRental->documents as $document)
                            <div class="col-md-6">
                                <div class="card border-left-success h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="{{ $document->icon_class }} fa-2x me-2"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-truncate small" title="{{ $document->document_name }}">
                                                    {{ $document->document_name }}
                                                </h6>
                                                <small class="text-muted d-block">{{ $document->formatted_file_size }}</small>
                                                @if($document->description)
                                                <small class="text-muted d-block">{{ $document->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm w-100">
                                            @if($document->isPdf())
                                            <a href="{{ route('admin.rentals.documents.view', $document->id) }}"
                                               target="_blank"
                                               class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @else
                                            <button type="button"
                                                    class="btn btn-info"
                                                    onclick="showDocumentModal('{{ $document->document_url }}', '{{ $document->document_name }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('admin.rentals.documents.download', $document->id) }}"
                                               class="btn btn-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Map Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-map me-2"></i>Lokasi di Peta</h5>
                </div>
                <div class="card-body p-0">
                    <iframe
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0"
                        src="https://www.google.com/maps?q={{ $halte->latitude }},{{ $halte->longitude }}&output=embed"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Photos Gallery Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Galeri Foto</h5>
                </div>
                <div class="card-body">
                    @if($halte->photos->count() > 0)
                        <div class="row g-2">
                            @foreach($halte->photos as $photo)
                                <div class="col-6">
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($photo->photo_path) }}"
                                             class="img-fluid rounded cursor-pointer"
                                             alt="Foto Halte"
                                             onclick="showImageModal('{{ Storage::url($photo->photo_path) }}', '{{ $photo->description ?? $halte->name }}')"
                                             style="cursor: pointer; height: 150px; width: 100%; object-fit: cover;">

                                        @if($photo->is_primary)
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                                <i class="fas fa-star me-1"></i>Utama
                                            </span>
                                        @endif

                                        @if($photo->description)
                                            <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 small">
                                                {{ Str::limit($photo->description, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>Belum ada foto</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rental History Card -->
            @if($halte->rentalHistories->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Sewa</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($halte->rentalHistories->take(5) as $history)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $history->rented_by }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($history->rent_start_date)->format('d M Y') }} -
                                                {{ \Carbon\Carbon::parse($history->rent_end_date)->format('d M Y') }}
                                            </small>
                                            {{-- RENTAL HISTORY DOCUMENTS - NEW --}}
                                            @if($history->hasDocuments())
                                            <small class="d-block mt-1 text-info">
                                                <i class="fas fa-paperclip me-1"></i>
                                                {{ $history->documents->count() }} dokumen terlampir
                                            </small>
                                            @endif
                                        </div>
                                        @if($history->rental_cost > 0)
                                            <span class="badge bg-success">
                                                Rp {{ number_format($history->rental_cost, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($history->notes)
                                        <p class="mb-0 mt-2 small text-secondary">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            {{ $history->notes }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($halte->rentalHistories->count() > 5)
                            <div class="card-footer text-center">
                                <a href="{{ route('admin.rentals.index', ['halte_id' => $halte->id]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Lihat Semua Riwayat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Foto Halte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid rounded" alt="Foto Halte">
            </div>
        </div>
    </div>
</div>

<!-- Document Modal for Images - NEW -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalDocument" class="img-fluid rounded" alt="Dokumen">
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<style>
    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-item i {
        font-size: 1.1rem;
    }

    .detail-item p {
        color: #495057;
    }

    .cursor-pointer {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .cursor-pointer:hover {
        transform: scale(1.05);
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
        padding: 1rem 1.25rem;
    }

    .badge {
        padding: 0.5rem 1rem;
    }

    .list-group-item {
        border-left: none;
        border-right: none;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .border-left-info {
        border-left: 4px solid #36b9cc;
    }

    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
</style>
@endpush

@push('scripts')
<script>
    // Show image in modal
    function showImageModal(imageSrc, description) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalLabel').textContent = description;

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        } else {
            $('#imageModal').modal('show');
        }
    }

    // Show document in modal - NEW FUNCTION
    function showDocumentModal(documentSrc, documentName) {
        document.getElementById('modalDocument').src = documentSrc;
        document.getElementById('documentModalLabel').textContent = documentName;

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(document.getElementById('documentModal')).show();
        } else {
            $('#documentModal').modal('show');
        }
    }

    // Confirm delete
    function confirmDelete(halteId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Halte?',
                text: "Data halte, semua foto, dan dokumen akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                customClass: {
                    popup: 'rounded-4 shadow-lg',
                    confirmButton: 'btn btn-danger px-4 py-2',
                    cancelButton: 'btn btn-secondary px-4 py-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = '{{ route("admin.haltes.destroy", ":id") }}'.replace(':id', halteId);
                    form.submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus halte ini? Semua data, foto, dan dokumen akan dihapus permanen!')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ route("admin.haltes.destroy", ":id") }}'.replace(':id', halteId);
                form.submit();
            }
        }
    }

    // Auto-dismiss alerts
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else if (typeof $ !== 'undefined') {
                    $(alert).fadeOut();
                }
            });
        }, 5000);
    });
</script>
@endpush
