{{-- resources/views/components/modal-access-restricted.blade.php --}}
<div class="modal-access-overlay" id="modalAccessRestricted" style="display: none;">
    <div class="modal-access-container">
        <div class="modal-access-content">
            <!-- Icon -->
            <div class="modal-access-icon">
                <div class="icon-circle">
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <!-- Title -->
            <h3 class="modal-access-title">
                <i class="fas fa-shield-alt me-2"></i>Akses Terbatas
            </h3>

            <!-- Message -->
            <p class="modal-access-message">
                Detail lengkap halte hanya dapat diakses oleh <strong>Admin yang terdaftar</strong>.
            </p>
            <p class="modal-access-submessage">
                Silakan login untuk melihat informasi detail halte bus.
            </p>

            <!-- Actions -->
            <div class="modal-access-actions">
                @guest
                <a href="{{ route('login') }}" class="btn-modal-access btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login sebagai Admin
                </a>
                @endguest
                <button type="button" class="btn-modal-access btn-close-modal" onclick="closeAccessModal()">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Access Restricted Styles */
.modal-access-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
    padding: 20px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-access-container {
    max-width: 500px;
    width: 100%;
    animation: slideUp 0.3s ease;
}

.modal-access-content {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.modal-access-icon {
    margin-bottom: 25px;
}

.icon-circle {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(220, 38, 38, 0.4);
    }
}

.icon-circle i {
    font-size: 36px;
    color: white;
}

.modal-access-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 15px;
}

.modal-access-title i {
    color: #dc2626;
}

.modal-access-message {
    font-size: 16px;
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 10px;
}

.modal-access-message strong {
    color: #dc2626;
    font-weight: 600;
}

.modal-access-submessage {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 30px;
}

.modal-access-actions {
    display: flex;
    gap: 12px;
    flex-direction: column;
}

.btn-modal-access {
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.btn-login {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
}

.btn-login:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    transform: translateY(-2px);
    color: white;
}

.btn-close-modal {
    background: #f3f4f6;
    color: #4b5563;
    border: 2px solid #e5e7eb;
}

.btn-close-modal:hover {
    background: #e5e7eb;
    border-color: #d1d5db;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 576px) {
    .modal-access-content {
        padding: 30px 20px;
    }

    .icon-circle {
        width: 70px;
        height: 70px;
    }

    .icon-circle i {
        font-size: 30px;
    }

    .modal-access-title {
        font-size: 20px;
    }

    .modal-access-message {
        font-size: 14px;
    }

    .btn-modal-access {
        padding: 12px 24px;
        font-size: 14px;
    }
}
</style>

<script>
// Function to show modal
function showAccessModal() {
    const modal = document.getElementById('modalAccessRestricted');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Function to close modal
function closeAccessModal() {
    const modal = document.getElementById('modalAccessRestricted');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalAccessRestricted');
    if (e.target === modal) {
        closeAccessModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAccessModal();
    }
});
</script>
