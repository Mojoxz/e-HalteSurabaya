// resources/js/admin/haltes-edit.js

document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toggle SIMBADA number field
    const simbadaCheckbox = document.getElementById('simbada_registered');
    if (simbadaCheckbox) {
        simbadaCheckbox.addEventListener('change', function() {
            const simbadaGroup = document.getElementById('simbada_number_group');
            if (this.checked) {
                simbadaGroup.style.display = 'block';
            } else {
                simbadaGroup.style.display = 'none';
                document.getElementById('simbada_number').value = '';
            }
        });
    }

    // Toggle rental details
    const isRentedCheckbox = document.getElementById('is_rented');
    if (isRentedCheckbox) {
        isRentedCheckbox.addEventListener('change', function() {
            const rentalDetails = document.getElementById('rental_details');
            if (this.checked) {
                rentalDetails.style.display = 'block';
            } else {
                rentalDetails.style.display = 'none';
                // Clear rental fields when hiding
                document.getElementById('rented_by').value = '';
                document.getElementById('rent_start_date').value = '';
                document.getElementById('rent_end_date').value = '';
                document.getElementById('rental_cost').value = '';
                document.getElementById('rental_notes').value = '';
            }
        });
    }

    // Validate rental dates
    const rentStartDate = document.getElementById('rent_start_date');
    if (rentStartDate) {
        rentStartDate.addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.getElementById('rent_end_date');

            if (startDate) {
                endDateInput.min = startDate;
                if (endDateInput.value && endDateInput.value < startDate) {
                    endDateInput.value = '';
                }
            }
        });
    }

    // Update coordinates display
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput) latInput.addEventListener('input', updateCoordinatesDisplay);
    if (lngInput) lngInput.addEventListener('input', updateCoordinatesDisplay);

    // Form submission with loading
    const halteForm = document.getElementById('halte-form');
    if (halteForm) {
        halteForm.addEventListener('submit', function() {
            document.getElementById('submit-btn').disabled = true;
            $('#loadingModal').modal('show');
        });
    }

    // Photo input change
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', previewImages);
    }
});

/**
 * Update coordinates display
 */
function updateCoordinatesDisplay() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    const coordsDisplay = document.getElementById('coords-display');
    if (coordsDisplay) {
        coordsDisplay.textContent = `${lat || '0'}, ${lng || '0'}`;
    }
}

/**
 * Delete photo with AJAX
 */
window.deletePhoto = function(photoId) {
    if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        return;
    }

    $.ajax({
        url: `/admin/haltes/photos/${photoId}`,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Remove photo element from DOM
                $(`#photo-${photoId}`).fadeOut(300, function() {
                    $(this).remove();

                    // Update photos count
                    const remainingPhotos = $('#existing-photos .col-6').length - 1;
                    $('.card-header h6').first().text(`Foto Saat Ini (${remainingPhotos})`);

                    if (remainingPhotos === 0) {
                        $('#existing-photos').parent().parent().hide();
                    }
                });

                // Show success message
                showAlert('success', response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showAlert('error', response ? response.message : 'Gagal menghapus foto');
        }
    });
};

/**
 * Set primary photo with AJAX
 */
window.setPrimaryPhoto = function(photoId) {
    $.ajax({
        url: `/admin/haltes/photos/${photoId}/primary`,
        type: 'PATCH',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Remove all primary badges
                $('.badge-primary').remove();
                $('.btn-warning').show();

                // Add primary badge to selected photo
                $(`#photo-${photoId}`).find('.position-relative').append(
                    '<span class="badge badge-primary position-absolute" style="top: 5px; left: 5px;"><i class="fas fa-star"></i> Utama</span>'
                );

                // Hide primary button for this photo
                $(`#photo-${photoId}`).find('.btn-warning').hide();

                // Show success message
                showAlert('success', response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showAlert('error', response ? response.message : 'Gagal mengatur foto utama');
        }
    });
};

/**
 * Preview uploaded images
 */
function previewImages() {
    const files = document.getElementById('photos').files;
    const previewContainer = document.getElementById('image-preview');
    const descriptionsContainer = document.getElementById('photo-descriptions');

    previewContainer.innerHTML = '';
    descriptionsContainer.innerHTML = '';

    if (files.length > 0) {
        descriptionsContainer.innerHTML = '<label>Deskripsi Foto:</label>';
    }

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-12 mb-2';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.width = '100%';
            img.style.height = '100px';
            img.style.objectFit = 'cover';

            const badge = document.createElement('span');
            badge.className = 'badge badge-secondary';
            badge.textContent = 'Foto Baru ' + (index + 1);
            badge.style.position = 'absolute';
            badge.style.top = '5px';
            badge.style.left = '5px';

            col.style.position = 'relative';
            col.appendChild(img);
            col.appendChild(badge);
            previewContainer.appendChild(col);
        };
        reader.readAsDataURL(file);

        // Add description input
        const descInput = document.createElement('input');
        descInput.type = 'text';
        descInput.name = 'photo_descriptions[]';
        descInput.className = 'form-control mb-2';
        descInput.placeholder = 'Deskripsi foto ' + (index + 1) + ' (opsional)';
        descriptionsContainer.appendChild(descInput);
    });
}

/**
 * Show alert messages
 */
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;

    // Remove existing alerts
    $('.alert').remove();

    // Add new alert at the top
    $('.container-fluid').prepend(alertHtml);

    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
