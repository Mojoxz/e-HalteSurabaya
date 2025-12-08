// resources/js/admin/rental/index.js

document.addEventListener('DOMContentLoaded', function() {
    // Auto-search functionality
    const form = document.getElementById('autoFilterForm');
    const autoSearchInputs = document.querySelectorAll('.auto-search');
    const filterIndicator = document.getElementById('filterIndicator');
    let searchTimeout;

    // Function to submit form
    function submitForm() {
        // Show loading indicator
        if (filterIndicator) {
            filterIndicator.style.display = 'inline-block';
        }

        // Submit form
        form.submit();
    }

    // Add event listeners to all auto-search inputs
    autoSearchInputs.forEach(input => {
        // For text input - use debounce (delay 500ms after user stops typing)
        if (input.type === 'text') {
            input.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    submitForm();
                }, 500); // Wait 500ms after user stops typing
            });
        }
        // For date and select - submit immediately
        else if (input.type === 'date' || input.tagName === 'SELECT') {
            input.addEventListener('change', function() {
                submitForm();
            });
        }
    });

    // Auto-collapse filter on mobile
    if (window.innerWidth < 768) {
        const filterCollapse = document.getElementById('filterCollapse');
        if (filterCollapse && !filterCollapse.classList.contains('show')) {
            // Check if there are active filters from the DOM
            const hasActiveFilters = document.querySelector('.badge.bg-success') !== null;
            if (!hasActiveFilters) {
                filterCollapse.classList.remove('show');
            }
        }
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Preserve filter state in pagination links
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        if (link.href) {
            const url = new URL(link.href);

            // Add current filter values to pagination links
            autoSearchInputs.forEach(input => {
                if (input.value) {
                    url.searchParams.set(input.name, input.value);
                }
            });

            link.href = url.toString();
        }
    });

    // Clear individual filter on ESC key
    autoSearchInputs.forEach(input => {
        if (input.type === 'text') {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    submitForm();
                }
            });
        }
    });

    // Add visual feedback for active filters
    autoSearchInputs.forEach(input => {
        if (input.value) {
            input.style.borderColor = '#4e73df';
            input.style.backgroundColor = '#f0f5ff';
        }
    });

    // Auto-dismiss alerts
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

// Show document in modal for images
window.showDocumentModal = function(documentSrc, documentName) {
    document.getElementById('modalDocumentImage').src = documentSrc;
    document.getElementById('documentImageModalLabel').textContent = documentName;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        new bootstrap.Modal(document.getElementById('documentImageModal')).show();
    } else if (typeof $ !== 'undefined') {
        $('#documentImageModal').modal('show');
    }
}
