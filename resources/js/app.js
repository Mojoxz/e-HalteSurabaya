// Animasi untuk elemen yang muncul saat di-scroll
document.addEventListener('DOMContentLoaded', function() {
    // Fade in body
    document.body.style.opacity = 1;

    // Animasi untuk elemen dengan class fade-in-scroll
    const fadeElements = document.querySelectorAll('.fade-in-scroll');

    const fadeOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const fadeObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, fadeOptions);

    fadeElements.forEach(element => {
        fadeObserver.observe(element);
    });

    // Animasi hover untuk dropdown items
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});

// POPUP LOGOUT FUNCTIONS
// Fungsi untuk menampilkan modal konfirmasi logout
window.showLogoutConfirmation = function() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('show');

    // Focus pada tombol "Batal" untuk accessibility
    setTimeout(() => {
        document.querySelector('.btn-cancel').focus();
    }, 100);
}

// Fungsi untuk membatalkan logout
window.cancelLogout = function() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('show');
}

// Fungsi untuk konfirmasi logout
window.confirmLogout = function() {
    const confirmBtn = document.getElementById('confirmBtn');
    const modal = document.getElementById('logoutModal');

    // Tampilkan loading state
    confirmBtn.classList.add('btn-loading');
    confirmBtn.innerHTML = '<span>Memproses...</span>';
    confirmBtn.disabled = true;

    // Buat form logout dan submit
    const logoutForm = document.createElement('form');
    logoutForm.method = 'POST';
    logoutForm.action = window.logoutRoute; // Will be set from blade

    // Tambahkan CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = window.csrfToken; // Will be set from blade
    logoutForm.appendChild(csrfToken);

    // Tambahkan form ke body dan submit
    document.body.appendChild(logoutForm);
    logoutForm.submit();
}

// Fungsi untuk menampilkan modal berhasil logout
window.showSuccessModal = function() {
    const modal = document.getElementById('successModal');
    modal.classList.add('show');

    // Tambahkan bounce animation pada icon
    setTimeout(() => {
        const successIcon = document.querySelector('#successModal .modal-icon');
        if (successIcon) {
            successIcon.classList.add('bounce');
        }
    }, 200);

    // Focus pada tombol untuk accessibility
    setTimeout(() => {
        const successBtn = document.querySelector('#successModal .btn-success');
        if (successBtn) {
            successBtn.focus();
        }
    }, 100);
}

// Fungsi untuk redirect ke beranda
window.redirectToHome = function() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('show');

    setTimeout(() => {
        window.location.href = window.homeRoute; // Will be set from blade
    }, 300);
}

// Event listener untuk ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const logoutModal = document.getElementById('logoutModal');
        const successModal = document.getElementById('successModal');

        if (logoutModal && logoutModal.classList.contains('show')) {
            cancelLogout();
        } else if (successModal && successModal.classList.contains('show')) {
            redirectToHome();
        }
    }
});

// Event listener untuk klik di overlay
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            if (overlay.id === 'logoutModal') {
                cancelLogout();
            } else if (overlay.id === 'successModal') {
                redirectToHome();
            }
        }
    });
});
