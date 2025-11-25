@extends('layouts.admin')

@section('title', 'Edit Halte')

@push('styles')
    @vite(['resources/css/admin/haltes-edit.css'])
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Halte: {{ $halte->name }}</h1>
        <a href="{{ route('admin.haltes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.haltes.update', $halte->id) }}" method="POST" enctype="multipart/form-data" id="halte-form">
        @csrf
        @method('PUT')
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
                                   value="{{ old('name', $halte->name) }}"
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
                                      placeholder="Masukkan deskripsi halte (opsional)">{{ old('description', $halte->description) }}</textarea>
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
                                      placeholder="Masukkan alamat lengkap halte">{{ old('address', $halte->address) }}</textarea>
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
                                           value="{{ old('latitude', $halte->latitude) }}"
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
                                           value="{{ old('longitude', $halte->longitude) }}"
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
                                       {{ old('simbada_registered', $halte->simbada_registered) ? 'checked' : '' }}>
                                <label class="form-check-label" for="simbada_registered">
                                    Terdaftar di SIMBADA
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="simbada_number_group" style="{{ old('simbada_registered', $halte->simbada_registered) ? 'display: block;' : 'display: none;' }}">
                            <label for="simbada_number">Nomor SIMBADA</label>
                            <input type="text"
                                   class="form-control @error('simbada_number') is-invalid @enderror"
                                   id="simbada_number"
                                   name="simbada_number"
                                   value="{{ old('simbada_number', $halte->simbada_number) }}"
                                   placeholder="Masukkan nomor SIMBADA">
                            @error('simbada_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rental Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Penyewaan</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_rented"
                                       name="is_rented"
                                       value="1"
                                       {{ old('is_rented', $halte->is_rented) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_rented">
                                    Halte sedang disewa
                                </label>
                            </div>
                        </div>

                        <div id="rental_details" style="{{ old('is_rented', $halte->is_rented) ? 'display: block;' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="rented_by">Disewa Oleh</label>
                                <input type="text"
                                       class="form-control @error('rented_by') is-invalid @enderror"
                                       id="rented_by"
                                       name="rented_by"
                                       value="{{ old('rented_by', $halte->rented_by) }}"
                                       placeholder="Nama penyewa atau perusahaan">
                                @error('rented_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rent_start_date">Tanggal Mulai Sewa</label>
                                        <input type="date"
                                               class="form-control @error('rent_start_date') is-invalid @enderror"
                                               id="rent_start_date"
                                               name="rent_start_date"
                                               value="{{ old('rent_start_date', $halte->rent_start_date ? $halte->rent_start_date->format('Y-m-d') : '') }}">
                                        @error('rent_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rent_end_date">Tanggal Berakhir Sewa</label>
                                        <input type="date"
                                               class="form-control @error('rent_end_date') is-invalid @enderror"
                                               id="rent_end_date"
                                               name="rent_end_date"
                                               value="{{ old('rent_end_date', $halte->rent_end_date ? $halte->rent_end_date->format('Y-m-d') : '') }}">
                                        @error('rent_end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rental_cost_display">Biaya Sewa</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text"
                                                class="form-control @error('rental_cost') is-invalid @enderror"
                                                id="rental_cost_display"
                                                placeholder="0"
                                                autocomplete="off">
                                            <!-- Hidden input untuk value asli -->
                                            <input type="hidden"
                                                id="rental_cost"
                                                name="rental_cost"
                                                value="{{ old('rental_cost', $halte->rentalHistories->first()->rental_cost ?? '') }}">
                                        </div>
                                        @error('rental_cost')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rental_notes">Catatan Penyewaan</label>
                                <textarea class="form-control @error('rental_notes') is-invalid @enderror"
                                          id="rental_notes"
                                          name="rental_notes"
                                          rows="3"
                                          placeholder="Catatan atau keterangan tambahan tentang penyewaan">{{ old('rental_notes', $halte->rentalHistories->first()->notes ?? '') }}</textarea>
                                @error('rental_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Existing Photos -->
                @if($halte->photos->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Foto Saat Ini ({{ $halte->photos->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row" id="existing-photos">
                            @foreach($halte->photos as $photo)
                            <div class="col-6 mb-3" id="photo-{{ $photo->id }}">
                                <div class="photo-container position-relative">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                         alt="{{ $photo->description }}"
                                         class="img-thumbnail w-100 photo-thumbnail">

                                    @if($photo->is_primary)
                                        <span class="badge badge-primary photo-badge">
                                            <i class="fas fa-star"></i> Utama
                                        </span>
                                    @endif

                                    <div class="btn-group photo-actions">
                                        @if(!$photo->is_primary)
                                            <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    title="Jadikan Utama"
                                                    onclick="setPrimaryPhoto({{ $photo->id }})">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endif
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus"
                                                onclick="deletePhoto({{ $photo->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    @if($photo->description)
                                        <small class="text-muted d-block mt-1">{{ $photo->description }}</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Upload New Photos -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Foto Baru</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="photos">Foto Halte</label>
                            <input type="file"
                                   class="form-control-file @error('photos.*') is-invalid @enderror"
                                   id="photos"
                                   name="photos[]"
                                   multiple
                                   accept="image/*">
                            <small class="form-text text-muted">
                                Pilih satu atau lebih foto. Format: JPG, JPEG, PNG, GIF. Maksimal 2MB per file.
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
                        <div id="map">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt text-primary fa-2x mb-2"></i><br>
                                <strong>Koordinat:</strong><br>
                                <span id="coords-display">{{ $halte->latitude }}, {{ $halte->longitude }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-save"></i> Update Halte
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

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memproses update...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/haltes-edit.js'])
@endpush
