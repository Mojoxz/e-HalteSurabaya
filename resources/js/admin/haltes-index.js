// Haltes Index Page JavaScript
// Import Bootstrap (if needed)
// import { Modal, Alert } from 'bootstrap';

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
 * Delete confirmation functionality
 */
let deleteHalteId = null;

window.confirmDelete = function(halteId, halteName) {
    deleteHalteId = halteId;

    const deleteNameEl = document.getElementById('deleteHalteName');
    if (deleteNameEl) {
        deleteNameEl.textContent = halteName;
    }

    const deleteModalEl = document.getElementById('deleteModal');
    if (deleteModalEl && typeof bootstrap !== 'undefined') {
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        deleteModal.show();
    }
}

/**
 * Handle delete confirmation button
 */
function initDeleteConfirmation() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (deleteHalteId) {
                // Add loading state
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
                this.disabled = true;

                const deleteForm = document.getElementById('delete-form-' + deleteHalteId);
                if (deleteForm) {
                    deleteForm.submit();
                }
            }
        });
    }
}

/**
 * Auto-hide alerts after 5 seconds
 */
function initAutoHideAlerts() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
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
 * Search form enhancement with auto-submit on filter change
 */
function initSearchForm() {
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const simbadaSelect = document.getElementById('simbada');
    const filterForm = document.getElementById('filterForm');

    if (!filterForm) return;

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
                e.preventDefault();
                filterForm.submit();
            }
        });

        // Clear search on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
            }
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
 * Form submission loading states
 */
function initFormSubmissionStates() {
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
}

/**
 * Initialize all functionality when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    initSortableHeaders();
    initDeleteConfirmation();
    initAutoHideAlerts();
    initImageErrorHandling();
    initSearchForm();
    initPaginationLoading();
    initFormSubmissionStates();
});

// Export functions if using module system
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showPhotoModal: window.showPhotoModal,
        confirmDelete: window.confirmDelete,
        initSortableHeaders,
        initDeleteConfirmation,
        initAutoHideAlerts,
        initImageErrorHandling,
        initSearchForm,
        initPaginationLoading,
        initFormSubmissionStates
    };
}
