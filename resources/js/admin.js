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

    // HAPUS bagian auto-hide alerts - karena sekarang pakai SweetAlert2
    // Alert akan ditampilkan langsung oleh SweetAlert2 dari layout admin

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

// Logout Modal Functions - DIGANTI dengan SweetAlert2
window.showLogoutConfirmation = function() {
    Swal.fire({
        title: 'Yakin ingin keluar?',
        text: "Apakah Anda yakin ingin keluar dari panel admin?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

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
    });
}

// HAPUS fungsi cancelLogout dan confirmLogout - tidak dipakai lagi
// Semua sudah dihandle oleh SweetAlert2

// Close modal with ESC key - TIDAK PERLU lagi karena SweetAlert2 sudah handle
// SweetAlert2 otomatis support ESC key

// HAPUS event listener untuk modal overlay click
// SweetAlert2 sudah handle ini secara otomatis

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

// Helper function untuk menampilkan SweetAlert dari JavaScript
window.showAlert = function(type, title, message, timer = null) {
    const icons = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };

    const colors = {
        'success': '#4CAF50',
        'error': '#f44336',
        'warning': '#ff9800',
        'info': '#2196F3'
    };

    const config = {
        icon: icons[type] || 'info',
        title: title,
        text: message,
        confirmButtonColor: colors[type] || '#2196F3',
        confirmButtonText: 'OK'
    };

    if (timer) {
        config.timer = timer;
        config.timerProgressBar = true;
    }

    Swal.fire(config);
};

// Function untuk konfirmasi delete dengan SweetAlert2
window.confirmDelete = function(itemName, deleteUrl) {
    Swal.fire({
        title: 'Hapus ' + itemName + '?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create and submit delete form
            const deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = deleteUrl;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            deleteForm.appendChild(csrfToken);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            deleteForm.appendChild(methodInput);

            document.body.appendChild(deleteForm);
            deleteForm.submit();
        }
    });
};
