@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user-plus"></i> Tambah User
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Debug Info (remove in production) -->
    <!-- @if(config('app.debug'))
    <div class="alert alert-info">
        <strong>Debug Info:</strong>
        <ul class="mb-0">
            <li>CSRF Token: {{ csrf_token() }}</li>
            <li>Form Action: {{ route('admin.users.store') }}</li>
            <li>Current User: {{ Auth::user()->name ?? 'Not logged in' }}</li>
        </ul>
    </div>
    @endif -->

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi User</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user"></i> Informasi Pribadi
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autocomplete="name"
                                           placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autocomplete="email"
                                           placeholder="contoh@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           autocomplete="tel"
                                           placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required
                                            autocomplete="off">
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                            User
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="3"
                                              autocomplete="street-address"
                                              placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-key"></i> Informasi Akun
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Minimal 8 karakter">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Ulangi password">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        User aktif (dapat login ke sistem)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Simpan User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Role</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-user-shield"></i> Admin
                        </h6>
                        <ul class="text-sm text-muted">
                            <li>Dapat mengakses dashboard admin</li>
                            <li>Mengelola data halte</li>
                            <li>Melihat riwayat sewa</li>
                            <li>Mengelola user lain</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-secondary">
                            <i class="fas fa-user"></i> User
                        </h6>
                        <ul class="text-sm text-muted">
                            <li>Dapat melihat peta halte</li>
                            <li>Melihat detail halte</li>
                            <li>Akses terbatas ke fitur admin</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tips:</strong> Password minimal 8 karakter dan harus dikonfirmasi dengan benar.
                    </div>
                </div>
            </div>

            <!-- Debug Panel (remove in production) -->
           <!-- @if(config('app.debug'))
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">Debug Panel</h6>
                </div>
                <div class="card-body">
                    <small>
                        <strong>Old Data:</strong><br>
                        Name: {{ old('name') ?? 'null' }}<br>
                        Email: {{ old('email') ?? 'null' }}<br>
                        Role: {{ old('role') ?? 'null' }}<br>
                        Active: {{ old('is_active') ? 'true' : 'false' }}
                    </small>
                </div>
            </div> -->
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission debugging
    const form = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        console.log('Form is being submitted...');

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        // Log form data for debugging
        const formData = new FormData(form);
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        // Re-enable button after 5 seconds (in case of issues)
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan User';
        }, 5000);
    });
});
</script>

<style>
.gap-2 {
    gap: 0.5rem;
}
.text-sm {
    font-size: 0.875rem;
}
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection
