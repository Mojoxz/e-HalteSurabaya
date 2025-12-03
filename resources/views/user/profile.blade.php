@extends('layouts.user')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@push('styles')
<style>
    /* Profile Card */
    .profile-card {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .profile-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--primary-dark) 100%);
    }

    .profile-card-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-card-body {
        padding: 24px;
    }

    .profile-card-footer {
        padding: 16px 24px;
        background: var(--primary-dark);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-card-footer small {
        color: var(--text-secondary);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    /* Profile Avatar */
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--hover-color) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        border: 4px solid rgba(108, 99, 255, 0.2);
        box-shadow: 0 8px 24px rgba(108, 99, 255, 0.3);
    }

    .profile-avatar i {
        font-size: 48px;
        color: white;
    }

    .profile-name {
        font-size: 22px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .profile-email {
        color: var(--text-secondary);
        font-size: 14px;
        margin-bottom: 12px;
    }

    .role-badge {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid rgba(16, 185, 129, 0.3);
        display: inline-block;
    }

    /* Form Section */
    .form-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--accent-color);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid rgba(108, 99, 255, 0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label {
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control {
        background: var(--primary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        background: var(--primary-dark);
        border-color: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
    }

    .form-control:disabled {
        background: var(--accent-dark);
        color: var(--text-secondary);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-text {
        color: var(--text-secondary);
        font-size: 12px;
        margin-top: 4px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Alert Box */
    .alert-info-custom {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-left: 4px solid #3b82f6;
        color: #3b82f6;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    /* Divider */
    .form-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        margin: 32px 0;
    }

    /* Buttons */
    .btn-custom {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--hover-color) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
    }

    .btn-custom-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(108, 99, 255, 0.4);
        color: white;
    }

    .btn-custom-secondary {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-secondary);
    }

    .btn-custom-secondary:hover {
        background: var(--secondary-dark);
        border-color: rgba(255, 255, 255, 0.3);
        color: var(--text-primary);
    }

    /* Statistics Card */
    .stats-card-horizontal {
        background: var(--secondary-dark);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 24px;
    }

    .stats-card-horizontal .profile-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: rgba(255, 255, 255, 0.05);
    }

    .stat-item {
        background: var(--secondary-dark);
        padding: 24px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-item:hover {
        background: var(--primary-dark);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-value.primary { color: var(--accent-color); }
    .stat-value.success { color: #10b981; }
    .stat-value.info { color: #3b82f6; }
    .stat-value.warning { color: #fb923c; }

    .stat-label {
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
    }

    /* Password Toggle */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px 8px;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: var(--text-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-card {
            margin-bottom: 16px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
        }

        .profile-avatar i {
            font-size: 36px;
        }

        .profile-name {
            font-size: 18px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-item {
            padding: 16px;
        }

        .stat-value {
            font-size: 24px;
        }

        .form-section-title {
            font-size: 15px;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
            margin-bottom: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Info Card -->
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="fas fa-user"></i> Informasi Profil</h5>
                </div>
                <div class="profile-card-body text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4 class="profile-name">{{ $user->name }}</h4>
                    <p class="profile-email">{{ $user->email }}</p>
                    <span class="role-badge">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="profile-card-footer">
                    <small>
                        <i class="fas fa-calendar"></i>
                        Bergabung: {{ $user->created_at->format('d/m/Y') }}
                    </small>
                    <small>
                        <i class="fas fa-clock"></i>
                        Login terakhir: {{ $user->last_login_formatted }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="fas fa-edit"></i> Edit Profil</h5>
                </div>
                <div class="profile-card-body">
                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="form-section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Dasar
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                       value="{{ $user->email }}" disabled>
                                <small class="form-text">Email tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                                <small class="form-text">Role tidak dapat diubah</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3"
                                      placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-divider"></div>

                        <!-- Change Password -->
                        <div class="form-section-title">
                            <i class="fas fa-lock"></i>
                            Ubah Password
                        </div>

                        <div class="alert-info-custom">
                            <i class="fas fa-info-circle"></i>
                            <span>Kosongkan field password jika tidak ingin mengubah password</span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password"
                                           placeholder="Masukkan password saat ini">
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                           id="new_password" name="new_password"
                                           placeholder="Masukkan password baru">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control"
                                           id="new_password_confirmation" name="new_password_confirmation"
                                           placeholder="Konfirmasi password baru">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-custom btn-custom-primary">
                                <i class="fas fa-save"></i>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('user.dashboard') }}" class="btn-custom btn-custom-secondary">
                                <i class="fas fa-times"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="stats-card-horizontal">
        <div class="profile-card-header">
            <h5><i class="fas fa-chart-bar"></i> Statistik Akun</h5>
        </div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value primary">{{ $user->created_at->diffInDays(now()) }}</div>
                <div class="stat-label">Hari Bergabung</div>
            </div>
            <div class="stat-item">
                <div class="stat-value success">{{ $user->last_login_at ? $user->last_login_at->diffInDays(now()) : 0 }}</div>
                <div class="stat-label">Hari Sejak Login Terakhir</div>
            </div>
            <div class="stat-item">
                <div class="stat-value info">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                <div class="stat-label">Status Akun</div>
            </div>
            <div class="stat-item">
                <div class="stat-value warning">{{ ucfirst($user->role) }}</div>
                <div class="stat-label">Level Akses</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation enhancement
    const form = document.querySelector('form');
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    if (form && currentPassword && newPassword && confirmPassword) {
        newPassword.addEventListener('input', function() {
            if (this.value && !currentPassword.value) {
                currentPassword.setCustomValidity('Password saat ini harus diisi jika ingin mengubah password');
            } else {
                currentPassword.setCustomValidity('');
            }
        });

        confirmPassword.addEventListener('input', function() {
            if (this.value !== newPassword.value) {
                this.setCustomValidity('Konfirmasi password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
