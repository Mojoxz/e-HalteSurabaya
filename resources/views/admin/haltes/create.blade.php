@extends('layouts.app')

@section('title', 'Tambah Halte')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Halte Baru</h1>
        <a href="{{ route('admin.haltes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.haltes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Halte</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Halte <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama halte"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Masukkan deskripsi halte (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="2"
                                      placeholder="Masukkan alamat lengkap halte">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('latitude') is-invalid @enderror"
                                           id="latitude"
                                           name="latitude"
                                           value="{{ old('latitude') }}"
                                           step="any"
                                           placeholder="-7.2575"
                                           required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('longitude') is-invalid @enderror"
                                           id="longitude"
                                           name="longitude"
                                           value="{{ old('longitude') }}"
                                           step="any"
                                           placeholder="112.7521"
                                           required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="simbada_registered"
                                       name="simbada_registered"
                                       value="1"
                                       {{ old('simbada_registered') ? 'checked' : '' }}>
                                <label class="form-check-label" for="simbada_registered">
                                    Terdaftar di SIMBADA
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="simbada_number_group" style="display: none;">
                            <label for="simbada_number">Nomor SIMBADA</label>
                            <input type="text"
                                   class="form-control @error('simbada_number') is-invalid @enderror"
                                   id="simbada_number"
                                   name="simbada_number"
                                   value="{{ old('simbada_number') }}"
                                   placeholder="Masukkan nomor SIMBADA">
                            @error('simbada_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Upload Foto</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="photos">Foto Halte</label>
                            <input type="file"
                                   class="form-control-file @error('photos.*') is-invalid @enderror"
                                   id="photos"
                                   name="photos[]"
                                   multiple
                                   accept="image/*"
                                   onchange="previewImages()">
                            <small class="form-text text-muted">
                                Pilih satu atau lebih foto. Format: JPG, JPEG, PNG, GIF. Maksimal 2MB per file.
                                Foto pertama akan dijadikan foto utama.
                            </small>
                            @error('photos.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="photo-descriptions"></div>
                        <div id="image-preview" class="row"></div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Preview Lokasi</h6>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px dashed #dee2e6;">
                            <span class="text-muted">
                                <i class="fas fa-map-marker-alt"></i><br>
                                Masukkan koordinat untuk preview
                            </span>
                        </div>
                        <small class="form-text text-muted mt-2">
                            Koordinat akan ditampilkan di peta setelah dimasukkan.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Halte
                        </button>
                        <a href="{{ route('admin.haltes.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Toggle SIMBADA number field
document.getElementById('simbada_registered').addEventListener('change', function() {
    const simbadaGroup = document.getElementById('simbada_number_group');
    if (this.checked) {
        simbadaGroup.style.display = 'block';
    } else {
        simbadaGroup.style.display = 'none';
        document.getElementById('simbada_number').value = '';
    }
});

// Initialize SIMBADA field visibility
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('simbada_registered');
    if (checkbox.checked) {
        document.getElementById('simbada_number_group').style.display = 'block';
    }
});

// Preview uploaded images
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

// Update map preview when coordinates change
document.getElementById('latitude').addEventListener('input', updateMapPreview);
document.getElementById('longitude').addEventListener('input', updateMapPreview);

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
</script>
@endsection
