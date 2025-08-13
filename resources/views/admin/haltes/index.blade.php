@extends('layouts.app')

@section('title', 'Kelola Halte')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-list"></i> Kelola Halte</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.haltes.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Halte
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.haltes.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama halte..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="simbada" class="form-select">
                            <option value="">SIMBADA</option>
                            <option value="1" {{ request('simbada') == '1' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="0" {{ request('simbada') == '0' ? 'selected' : '' }}>Belum Terdaftar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Halte List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Halte ({{ $haltes->total() }} total)</h5>
        </div>
        <div class="card-body">
            @if($haltes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama Halte</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>SIMBADA</th>
                                <th>Sewa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($haltes as $halte)
                            <tr>
                                <td>
                                    @if($halte->primaryPhoto)
                                        <img src="{{ $halte->primary_photo_url }}" alt="{{ $halte->name }}"
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light text-center d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $halte->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($halte->description, 50) }}</small>
                                </td>
                                <td>
                                    <small>{{ $halte->address }}</small>
                                    <br>
                                    <small class="text-muted">{{ $halte->latitude }}, {{ $halte->longitude }}</small>
                                </td>
                                <td>
                                    @if($halte->isCurrentlyRented())
                                        <span class="badge bg-danger">Disewa</span>
                                    @else
                                        <span class="badge bg-success">Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    @if($halte->simbada_registered)
                                        <span class="badge bg-info">Terdaftar</span>
                                        @if($halte->simbada_number)
                                            <br><small class="text-muted">{{ $halte->simbada_number }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    @if($halte->isCurrentlyRented())
                                        <small>
                                            <strong>{{ $halte->rented_by }}</strong><br>
                                            {{ $halte->rent_start_date->format('d/m/Y') }} -
                                            {{ $halte->rent_end_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <small class="text-muted">Tidak disewa</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.haltes.show', $halte->id) }}"
                                           class="btn btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.haltes.edit', $halte->id) }}"
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.haltes.destroy', $halte->id) }}"
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus halte ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $haltes->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-bus fa-3x text-muted mb-3"></i>
                    <h5>Belum ada data halte</h5>
                    <p class="text-muted">Klik tombol "Tambah Halte" untuk mulai menambahkan data halte.</p>
                    <a href="{{ route('admin.haltes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Halte Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
