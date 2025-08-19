@extends('layouts.app')

@section('title', 'Detail User - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user"></i> Detail User
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pribadi</h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-xl mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-primary text-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <div class="mb-3">
                        <span class="badge {{ $user->role_badge ?? 'bg-secondary' }} me-2">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        @if(isset($user->status_badge))
                        <span class="badge {{ $user->status_badge }}">
                            {{ $user->status_text }}
                        </span>
                        @endif
                    </div>

                    @if($user->id === Auth::id())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Ini adalah akun Anda
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kontak</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-envelope text-primary"></i></td>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-phone text-success"></i></td>
                            <td><strong>Telepon:</strong></td>
                            <td>{{ $user->formatted_phone ?? ($user->phone ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-map-marker-alt text-danger"></i></td>
                            <td><strong>Alamat:</strong></td>
                            <td>{{ $user->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Akun</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Informasi Sistem</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge {{ $user->role_badge ?? 'bg-secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                @if(isset($user->is_active))
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $user->status_badge }}">
                                            {{ $user->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Terdaftar:</strong></td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if(isset($user->last_login_at))
                                <tr>
                                    <td><strong>Login Terakhir:</strong></td>
                                    <td>{{ $user->last_login_formatted }}</td>
                                </tr>
                                @endif
                                @if($user->creator)
                                <tr>
                                    <td><strong>Dibuat oleh:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->creator->id) }}"
                                           class="text-decoration-none">
                                            {{ $user->creator->name }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Statistik Aktivitas</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-history fa-2x text-info mb-2"></i>
                                            <h5 class="mb-1">{{ $user->rentalHistories ? $user->rentalHistories->count() : 0 }}</h5>
                                            <small class="text-muted">Riwayat Sewa Dibuat</small>
                                        </div>
                                    </div>
                                </div>
                                @if($user->role === 'admin')
                                <div class="col-12 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                                            <h5 class="mb-1">{{ $user->created_users_count ?? 0 }}</h5>
                                            <small class="text-muted">User Dibuat</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($user->rentalHistories && $user->rentalHistories->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Halte</th>
                                    <th>Penyewa</th>
                                    <th>Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->rentalHistories->take(5) as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($history->halte)
                                            {{ $history->halte->name }}
                                        @else
                                            <span class="text-muted">Halte dihapus</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->rented_by ?? '-' }}</td>
                                    <td>
                                        @if(isset($history->rental_cost))
                                            Rp {{ number_format($history->rental_cost, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($user->rentalHistories->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.rentals.index') }}?user_id={{ $user->id }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list"></i> Lihat Semua Riwayat
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas</h6>
                </div>
                <div class="card-body text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Aktivitas</h5>
                    <p class="text-muted">User ini belum memiliki riwayat aktivitas</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 80px;
    height: 80px;
}
.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
}
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection
