// resources/js/admin/halte-show.js

// Show image in modal
window.showImageModal = function(imageSrc, description) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalLabel').textContent = description;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    } else {
        $('#imageModal').modal('show');
    }
}

// Show document in modal - NEW FUNCTION
window.showDocumentModal = function(documentSrc, documentName) {
    document.getElementById('modalDocument').src = documentSrc;
    document.getElementById('documentModalLabel').textContent = documentName;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        new bootstrap.Modal(document.getElementById('documentModal')).show();
    } else {
        $('#documentModal').modal('show');
    }
}

// Confirm delete
window.confirmDelete = function(halteId) {
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
                form.action = window.halteDeleteRoute.replace(':id', halteId);
                form.submit();
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin menghapus halte ini? Semua data, foto, dan dokumen akan dihapus permanen!')) {
            const form = document.getElementById('deleteForm');
            form.action = window.halteDeleteRoute.replace(':id', halteId);
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
