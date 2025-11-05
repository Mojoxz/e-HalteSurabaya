// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Fade in body
    document.body.style.opacity = 1;

    // Initialize main content adjustment for sidebar
    const mainContent = document.getElementById('mainContent');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebar && mainContent) {
        // Check if sidebar is collapsed from localStorage
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            mainContent.classList.add('collapsed');
        }
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Add loading state to buttons when forms are submitted
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('btn-cancel')) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
            }
        });
    });
});

// Logout Modal Functions
window.showLogoutConfirmation = function() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('show');
    // Focus pada tombol cancel setelah modal muncul
    setTimeout(() => {
        const cancelBtn = modal.querySelector('.btn-cancel-confirm');
        if (cancelBtn) cancelBtn.focus();
    }, 100);
}

window.cancelLogout = function() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('show');
}

window.confirmLogout = function() {
    const confirmBtn = document.getElementById('confirmBtn');

    // Tambahkan loading state
    confirmBtn.classList.add('btn-loading');
    confirmBtn.innerHTML = '<span>Memproses...</span>';
    confirmBtn.disabled = true;

    // Create and submit logout form
    const logoutForm = document.createElement('form');
    logoutForm.method = 'POST';
    logoutForm.action = window.logoutRoute || '/logout';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    logoutForm.appendChild(csrfToken);

    document.body.appendChild(logoutForm);
    logoutForm.submit();
}

// Close modal with ESC key or overlay click
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const logoutModal = document.getElementById('logoutModal');
        if (logoutModal && logoutModal.classList.contains('show')) {
            window.cancelLogout();
        }
    }
});

// Close modal when clicking overlay
document.addEventListener('DOMContentLoaded', function() {
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.cancelLogout();
            }
        });
    }
});

// Update main content when sidebar state changes
window.addEventListener('storage', function(e) {
    if (e.key === 'sidebarCollapsed') {
        const mainContent = document.getElementById('mainContent');
        if (mainContent) {
            if (e.newValue === 'true') {
                mainContent.classList.add('collapsed');
            } else {
                mainContent.classList.remove('collapsed');
            }
        }
    }
});
