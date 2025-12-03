@extends('layouts.user')

@section('title', 'Daftar Halte')
@section('page-title', 'Daftar Halte')

@push('styles')
<style>
    /* Dark Theme Cards */
    .card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(108, 99, 255, 0.2);
        border-color: rgba(108, 99, 255, 0.3);
    }

    .card-body {
        padding: 20px;
    }

    /* Form Controls */
    .form-control, .form-select {
        background: var(--primary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        padding: 12px 16px;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        background: var(--primary-dark);
        border-color: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    .form-select option {
        background: var(--primary-dark);
        color: var(--text-primary);
    }

    .input-group-text {
        background: var(--primary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-secondary);
        border-radius: 12px 0 0 12px;
    }

    .input-group .form-control {
        border-radius: 0 12px 12px 0;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--hover-color) 100%);
        border: none;
        color: var(--text-primary);
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(108, 99, 255, 0.3);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-secondary);
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: var(--secondary-dark);
        border-color: var(--text-primary);
        color: var(--text-primary);
    }

    /* Card Images */
    .card-img-top {
        border-radius: 16px 16px 0 0;
        height: 200px;
        object-fit: cover;
    }

    /* Card Title */
    .card-title {
        color: var(--text-primary);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .card-text {
        color: var(--text-secondary);
        font-size: 14px;
        line-height: 1.6;
    }

    /* Badges */
    .badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
    }

    .bg-success {
        background: rgba(16, 185, 129, 0.2) !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .bg-warning {
        background: rgba(245, 158, 11, 0.2) !important;
        color: #f59e0b !important;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Small text */
    .text-muted {
        color: var(--text-secondary) !important;
    }

    /* Loading State */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(26, 29, 35, 0.8);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-overlay.show {
        display: flex;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(108, 99, 255, 0.2);
        border-top-color: var(--accent-color);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Pagination - Simplified and Clean */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 8px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
        padding: 0 12px;
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .page-link:hover {
        background: var(--accent-dark);
        border-color: var(--accent-color);
        color: var(--text-primary);
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--hover-color) 100%);
        border-color: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
        cursor: default;
    }

    .page-item.disabled .page-link {
        background: var(--primary-dark);
        border-color: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary);
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .page-item.disabled .page-link:hover {
        transform: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: var(--text-secondary);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h5 {
        color: var(--text-primary);
        margin-bottom: 12px;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 24px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 16px;
        }

        .btn-primary, .btn-outline-secondary {
            padding: 10px 16px;
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.haltes.index') }}" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" id="searchInput"
                                   placeholder="Cari halte..." value="{{ request('search') }}"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Haltes Grid -->
    <div class="row" id="haltesGrid">
        @forelse($haltes as $halte)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                @php
                    $primaryPhoto = $halte->photos->where('is_primary', true)->first();
                    $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                        ? asset('storage/' . $primaryPhoto->photo_path)
                        : asset('images/halte-default.png');
                @endphp
                <img src="{{ $photoUrl }}" class="card-img-top" alt="{{ $halte->name }}">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $halte->name }}</h5>

                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt"></i> {{ $halte->address }}
                        </small>
                    </div>

                    @if($halte->description)
                    <p class="card-text small">{{ Str::limit($halte->description, 100) }}</p>
                    @endif

                    @php
                        $isRented = $halte->isCurrentlyRented();
                    @endphp

                    <div class="mb-3">
                        <span class="badge {{ $isRented ? 'bg-warning' : 'bg-success' }}">
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
                <div class="card-body empty-state">
                    <i class="fas fa-search"></i>
                    <h5>Tidak ada halte yang ditemukan</h5>
                    <p>Coba ubah kriteria pencarian Anda</p>
                    <button onclick="resetFilters()" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($haltes->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Pagination">
            <ul class="pagination" id="customPagination">
                {{-- Previous Page Link --}}
                @if ($haltes->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">←</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $haltes->previousPageUrl() }}" rel="prev">←</a>
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
                        <a class="page-link" href="{{ $haltes->nextPageUrl() }}" rel="next">→</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">→</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout = null;
    const loadingOverlay = document.getElementById('loadingOverlay');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    const haltesGrid = document.getElementById('haltesGrid');
    const paginationContainer = document.getElementById('paginationContainer');

    // Live Search Function
    function performSearch() {
        const searchValue = searchInput.value;
        const statusValue = statusFilter.value;

        // Build URL with query parameters
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchValue);
        url.searchParams.set('status', statusValue);

        // Show loading
        loadingOverlay.classList.add('show');

        // Fetch results
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update grid
            const newGrid = doc.getElementById('haltesGrid');
            if (newGrid) {
                haltesGrid.innerHTML = newGrid.innerHTML;
            }

            // Update pagination
            const newPagination = doc.getElementById('paginationContainer');
            if (newPagination) {
                if (paginationContainer) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
            } else {
                if (paginationContainer) {
                    paginationContainer.innerHTML = '';
                }
            }

            // Update URL without reload
            window.history.pushState({}, '', url.toString());

            // Hide loading
            loadingOverlay.classList.remove('show');
        })
        .catch(error => {
            console.error('Search error:', error);
            loadingOverlay.classList.remove('show');
        });
    }

    // Search input with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500); // Wait 500ms after user stops typing
    });

    // Status filter - instant search
    statusFilter.addEventListener('change', function() {
        performSearch();
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        performSearch();
    });

    // Function for empty state reset button
    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = '';
        performSearch();
    }

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.page-link');

        if (paginationLink && !paginationLink.closest('.page-item.disabled') && !paginationLink.closest('.page-item.active')) {
            e.preventDefault();
            const url = paginationLink.getAttribute('href');

            if (url && url !== '#') {
                loadingOverlay.classList.add('show');

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newGrid = doc.getElementById('haltesGrid');
                    if (newGrid) {
                        haltesGrid.innerHTML = newGrid.innerHTML;
                    }

                    const newPagination = doc.getElementById('paginationContainer');
                    if (newPagination && paginationContainer) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }

                    window.history.pushState({}, '', url);

                    // Smooth scroll to top of content
                    const contentArea = document.querySelector('.content-area');
                    if (contentArea) {
                        contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }

                    loadingOverlay.classList.remove('show');
                })
                .catch(error => {
                    console.error('Pagination error:', error);
                    loadingOverlay.classList.remove('show');
                });
            }
        }
    });

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        location.reload();
    });
</script>
@endpush
