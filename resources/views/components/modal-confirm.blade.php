{{-- Modal Konfirmasi Component --}}
<div class="modal-overlay" id="{{ $id ?? 'confirmModal' }}">
    <div class="modal-popup">
        <div class="modal-header-confirm">
            <div class="modal-icon-confirm {{ $iconClass ?? 'icon-warning' }}">
                <i class="{{ $icon ?? 'fas fa-exclamation-triangle' }}"></i>
            </div>
            <h3 class="modal-title-confirm">{{ $title ?? 'Konfirmasi' }}</h3>
            <p class="modal-message-confirm">
                {{ $message ?? 'Apakah Anda yakin?' }}
            </p>
        </div>
        <div class="modal-footer-confirm">
            <button class="btn-modal-confirm btn-cancel-confirm" onclick="{{ $cancelAction ?? 'closeModal()' }}">
                <i class="fas fa-times"></i>
                {{ $cancelText ?? 'Batal' }}
            </button>
            <button class="btn-modal-confirm btn-confirm-confirm {{ $confirmClass ?? 'btn-danger' }}"
                    onclick="{{ $confirmAction ?? 'confirmAction()' }}"
                    id="{{ $confirmBtnId ?? 'confirmBtn' }}">
                <i class="{{ $confirmIcon ?? 'fas fa-check' }}"></i>
                {{ $confirmText ?? 'Ya' }}
            </button>
        </div>
    </div>
</div>
