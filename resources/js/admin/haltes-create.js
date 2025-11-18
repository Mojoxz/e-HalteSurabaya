// resources/js/admin/haltes-create.js

document.addEventListener('DOMContentLoaded', function() {
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

        // Initialize field visibility on page load
        if (simbadaCheckbox.checked) {
            document.getElementById('simbada_number_group').style.display = 'block';
        }
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

        // Initialize rental field visibility on page load
        if (isRentedCheckbox.checked) {
            document.getElementById('rental_details').style.display = 'block';
        }
    }

    // Validate rental dates
    const rentStartDate = document.getElementById('rent_start_date');
    if (rentStartDate) {
        rentStartDate.addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.getElementById('rent_end_date');

            if (startDate) {
                endDateInput.min = startDate;
                // If end date is before start date, clear it
                if (endDateInput.value && endDateInput.value < startDate) {
                    endDateInput.value = '';
                }
            }
        });
    }

    // Update map preview when coordinates change
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput) latInput.addEventListener('input', updateMapPreview);
    if (lngInput) lngInput.addEventListener('input', updateMapPreview);

    // Photo input change
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', previewImages);
    }
});

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
            badge.className = index === 0 ? 'badge badge-primary' : 'badge badge-secondary';
            badge.textContent = index === 0 ? 'Foto Utama' : 'Foto ' + (index + 1);
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
 * Update map preview when coordinates change
 */
function updateMapPreview() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    const mapDiv = document.getElementById('map');

    if (lat && lng) {
        mapDiv.innerHTML = `
            <div class="text-center">
                <i class="fas fa-map-marker-alt text-primary fa-2x mb-2"></i><br>
                <strong>Koordinat:</strong><br>
                ${lat}, ${lng}<br>
                <small class="text-muted">Preview peta akan ditampilkan saat halte disimpan</small>
            </div>
        `;
    } else {
        mapDiv.innerHTML = `
            <span class="text-muted">
                <i class="fas fa-map-marker-alt"></i><br>
                Masukkan koordinat untuk preview
            </span>
        `;
    }
}
