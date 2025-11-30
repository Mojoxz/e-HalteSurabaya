// resources/js/admin/haltes-create.js - UPDATED WITH DOCUMENT PREVIEW

document.addEventListener('DOMContentLoaded', function() {
    // Toggle SIMBADA number field and document upload
    const simbadaCheckbox = document.getElementById('simbada_registered');
    if (simbadaCheckbox) {
        simbadaCheckbox.addEventListener('change', function() {
            const simbadaGroup = document.getElementById('simbada_number_group');
            const simbadaDocGroup = document.getElementById('simbada_document_group');
            if (this.checked) {
                simbadaGroup.style.display = 'block';
                simbadaDocGroup.style.display = 'block';
            } else {
                simbadaGroup.style.display = 'none';
                simbadaDocGroup.style.display = 'none';
                document.getElementById('simbada_number').value = '';
                document.getElementById('simbada_documents').value = '';
                document.getElementById('simbada_document_descriptions').innerHTML = '';
                document.getElementById('simbada_document_preview').innerHTML = '';
            }
        });

        // Initialize field visibility on page load
        if (simbadaCheckbox.checked) {
            document.getElementById('simbada_number_group').style.display = 'block';
            document.getElementById('simbada_document_group').style.display = 'block';
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
                document.getElementById('rental_cost_display').value = '';
                document.getElementById('rental_notes').value = '';
                document.getElementById('rental_documents').value = '';
                document.getElementById('rental_document_descriptions').innerHTML = '';
                document.getElementById('rental_document_preview').innerHTML = '';
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
                if (endDateInput.value && endDateInput.value < startDate) {
                    endDateInput.value = '';
                }
            }
        });
    }

    // Format Rental Cost
    const rentalCostInput = document.getElementById('rental_cost');
    const rentalCostDisplay = document.getElementById('rental_cost_display');

    if (rentalCostInput && rentalCostDisplay) {
        if (rentalCostInput.value) {
            rentalCostDisplay.value = formatRupiah(rentalCostInput.value);
        }

        rentalCostDisplay.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            this.value = formatRupiah(value);
            rentalCostInput.value = value;
        });

        rentalCostDisplay.addEventListener('paste', function(e) {
            e.preventDefault();
            let pastedData = (e.clipboardData || window.clipboardData).getData('text');
            let value = pastedData.replace(/[^\d]/g, '');
            this.value = formatRupiah(value);
            rentalCostInput.value = value;
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

    // SIMBADA document input change - NEW
    const simbadaDocsInput = document.getElementById('simbada_documents');
    if (simbadaDocsInput) {
        simbadaDocsInput.addEventListener('change', function() {
            previewDocuments(this, 'simbada_document_descriptions', 'simbada_document_preview', 'simbada_document_descriptions');
        });
    }

    // Rental document input change - NEW
    const rentalDocsInput = document.getElementById('rental_documents');
    if (rentalDocsInput) {
        rentalDocsInput.addEventListener('change', function() {
            previewDocuments(this, 'rental_document_descriptions', 'rental_document_preview', 'rental_document_descriptions');
        });
    }
});

/**
 * Format number to Rupiah format
 */
function formatRupiah(angka) {
    if (!angka) return '';

    let number_string = angka.toString().replace(/[^,\d]/g, '');
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

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
 * Preview uploaded documents - NEW FUNCTION
 */
function previewDocuments(input, descContainerId, previewContainerId, descInputName) {
    const files = input.files;
    const previewContainer = document.getElementById(previewContainerId);
    const descriptionsContainer = document.getElementById(descContainerId);

    previewContainer.innerHTML = '';
    descriptionsContainer.innerHTML = '';

    if (files.length > 0) {
        descriptionsContainer.innerHTML = '<label class="mt-2">Deskripsi Dokumen:</label>';
    }

    Array.from(files).forEach((file, index) => {
        const fileName = file.name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB

        // Create preview card
        const col = document.createElement('div');
        col.className = 'col-12 mb-2';

        const card = document.createElement('div');
        card.className = 'card';
        card.style.fontSize = '0.85rem';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body p-2 d-flex align-items-center';

        // Icon based on file type
        const icon = document.createElement('i');
        if (fileExt === 'pdf') {
            icon.className = 'fas fa-file-pdf fa-2x text-danger me-2';
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            icon.className = 'fas fa-file-image fa-2x text-primary me-2';
        } else {
            icon.className = 'fas fa-file fa-2x text-secondary me-2';
        }

        const textDiv = document.createElement('div');
        textDiv.className = 'flex-grow-1';
        textDiv.innerHTML = `
            <strong>${fileName}</strong><br>
            <small class="text-muted">${fileSize} MB • ${fileExt.toUpperCase()}</small>
        `;

        cardBody.appendChild(icon);
        cardBody.appendChild(textDiv);
        card.appendChild(cardBody);
        col.appendChild(card);
        previewContainer.appendChild(col);

        // Add description input
        const descInput = document.createElement('input');
        descInput.type = 'text';
        descInput.name = descInputName + '[]';
        descInput.className = 'form-control mb-2';
        descInput.placeholder = 'Deskripsi dokumen ' + (index + 1) + ' (opsional)';
        descriptionsContainer.appendChild(descInput);

        // If it's an image, show preview
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail mt-1';
                img.style.width = '100%';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                col.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
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
