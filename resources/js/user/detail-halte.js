document.addEventListener('DOMContentLoaded', function() {
    // Photo modal handler
    const photoModal = document.getElementById('photoModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');

    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-bs-src');
            const caption = button.getAttribute('data-bs-caption');

            modalImage.src = src;
            modalCaption.textContent = caption;
        });
    }
});
