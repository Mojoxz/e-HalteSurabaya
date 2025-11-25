// Haltes Index Page JavaScript dengan Auto Search dan SweetAlert2

/**
 * Initialize sortable headers functionality
 */
function initSortableHeaders() {
    const sortableHeaders = document.querySelectorAll('.sortable-header');

    sortableHeaders.forEach(header => {
        header.style.cursor = 'pointer';

        header.addEventListener('click', function() {
            const sortField = this.dataset.sort;
            const currentSort = this.dataset.currentSort || '';
            const currentDirection = this.dataset.currentDirection || '';

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

        // Add hover effect
        header.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(255,255,255,0.1)';
        });

        header.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

/**
 * Show photo in modal
 */
window.showPhotoModal = function(photoUrl, halteName) {
    const modalPhoto = document.getElementById('modalPhoto');
    const modalLabel = document.getElementById('photoModalLabel');

    if (modalPhoto) {
        modalPhoto.src = photoUrl;
        modalPhoto.alt = halteName;
    }

    if (modalLabel) {
        modalLabel.textContent = 'Foto ' + halteName;
    }

    const photoModalEl = document.getElementById('photoModal');
    if (photoModalEl && typeof bootstrap !== 'undefined') {
        const photoModal = new bootstrap.Modal(photoModalEl);
        photoModal.show();
    }
}

/**
 * Delete confirmation dengan SweetAlert2
 */
window.confirmDelete = function(halteId, halteName) {
    // Cek apakah SweetAlert2 tersedia
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 tidak ditemukan!');
        // Fallback ke konfirmasi browser default
        if (confirm('Apakah Anda yakin ingin menghapus halte: ' + halteName + '?')) {
            document.getElementById('delete-form-' + halteId).submit();
        }
        return;
    }

    // Tampilkan SweetAlert2 konfirmasi
    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Apakah Anda yakin menghapus halte:<br><strong class="text-danger">${halteName}</strong>?<br><br>
               <small class="text-muted">Tindakan ini tidak dapat dibatalkan. Semua foto dan data terkait akan ikut terhapus.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#F44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes',
        cancelButtonText: '<i class="fas fa-times me-2"></i>No',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            confirmButton: 'btn btn-danger px-4 py-2 me-2',
            cancelButton: 'btn btn-secondary px-4 py-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user klik Yes, submit form
            const deleteForm = document.getElementById('delete-form-' + halteId);
            if (deleteForm) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                deleteForm.submit();
            }
        }
    });
}

/**
 * Tampilkan SweetAlert sukses jika ada session success
 */
function showSuccessAlert() {
    const successAlert = document.querySelector('.alert-success');

    if (successAlert && typeof Swal !== 'undefined') {
        const successMessage = successAlert.textContent.trim();
        successAlert.remove();

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage.replace(/×/g, '').trim(),
            confirmButtonColor: '#198754',
            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                confirmButton: 'btn btn-success px-4 py-2'
            },
            buttonsStyling: false
        });
    }
}

/**
 * Tampilkan SweetAlert error jika ada session error
 */
function showErrorAlert() {
    const errorAlert = document.querySelector('.alert-danger');

    if (errorAlert && typeof Swal !== 'undefined') {
        const errorMessage = errorAlert.textContent.trim();
        errorAlert.remove();

        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage.replace(/×/g, '').trim(),
            confirmButtonColor: '#dc3545',
            confirmButtonText: '<i class="fas fa-times me-2"></i>OK',
            customClass: {
                confirmButton: 'btn btn-danger px-4 py-2'
            },
            buttonsStyling: false
        });
    }
}

/**
 * Auto-hide alerts after 5 seconds
 */
function initAutoHideAlerts() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-success):not(.alert-danger)');
        alerts.forEach(function(alert) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
}

/**
 * Enhanced image error handling
 */
function initImageErrorHandling() {
    document.querySelectorAll('img[onerror]').forEach(function(img) {
        img.addEventListener('error', function() {
            console.log('Failed to load image:', this.src);
        });
    });
}

/**
 * AUTO SEARCH FUNCTIONALITY - REVISED
 * Pencarian otomatis tanpa perlu klik tombol Cari
 */
function initAutoSearch() {
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const simbadaSelect = document.getElementById('simbada');
    const filterForm = document.getElementById('filterForm');
    const searchBtn = document.querySelector('#filterForm button[type="submit"]');

    if (!filterForm) return;

    let searchTimeout;
    let isSearching = false;

    /**
     * Function to submit form with loading indicator
     */
    function submitFormWithLoading(trigger) {
        if (isSearching) return;

        isSearching = true;

        // Show loading indicator on the trigger element
        if (trigger && trigger !== searchInput) {
            const originalHTML = trigger.innerHTML;
            trigger.disabled = true;
            trigger.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }

        // Optional: Show global loading indicator
        if (searchBtn) {
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mencari...';
            searchBtn.disabled = true;
        }

        // Submit form
        filterForm.submit();
    }

    /**
     * AUTO SUBMIT on dropdown change (Status & SIMBADA)
     */
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            submitFormWithLoading(this);
        });
    }

    if (simbadaSelect) {
        simbadaSelect.addEventListener('change', function() {
            submitFormWithLoading(this);
        });
    }

    /**
     * AUTO SUBMIT on search input with debounce (delay 800ms)
     */
    if (searchInput) {
        // Real-time search with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            // Visual feedback - show that search is pending
            if (searchBtn) {
                searchBtn.innerHTML = '<i class="fas fa-clock me-2"></i> Menunggu...';
            }

            // Debounce - wait 800ms after user stops typing
            searchTimeout = setTimeout(function() {
                if (!isSearching) {
                    submitFormWithLoading(searchInput);
                }
            }, 800);
        });

        // Instant search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                submitFormWithLoading(searchInput);
            }
        });

        // Clear search on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearTimeout(searchTimeout);
                this.value = '';

                // Auto-submit after clearing
                setTimeout(function() {
                    if (!isSearching) {
                        submitFormWithLoading(searchInput);
                    }
                }, 100);
            }
        });
    }

    /**
     * Hide manual search button (optional)
     * Uncomment if you want to completely remove the search button
     */
    // if (searchBtn) {
    //     searchBtn.style.display = 'none';
    // }

    /**
     * Keep search button functional for manual trigger
     */
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            submitFormWithLoading(this);
        });
    }
}

/**
 * Add loading state for pagination links
 */
function initPaginationLoading() {
    const paginationLinks = document.querySelectorAll('.pagination .page-link');

    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const pageItem = this.closest('.page-item');

            if (!pageItem.classList.contains('disabled') &&
                !pageItem.classList.contains('active')) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
        });
    });
}

/**
 * Initialize all functionality when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    initSortableHeaders();
    initAutoHideAlerts();
    initImageErrorHandling();
    initAutoSearch(); // NEW: Replace initSearchForm with initAutoSearch
    initPaginationLoading();

    // Tampilkan SweetAlert untuk success/error messages
    showSuccessAlert();
    showErrorAlert();
});

// Export functions if using module system
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showPhotoModal: window.showPhotoModal,
        confirmDelete: window.confirmDelete,
        initSortableHeaders,
        initAutoHideAlerts,
        initImageErrorHandling,
        initAutoSearch,
        initPaginationLoading,
        showSuccessAlert,
        showErrorAlert
    };
}
