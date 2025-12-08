{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penyewaan')

@push('styles')
    @vite(['resources/css/admin/reports/index.css'])
@endpush

@section('content')
<div class="container-fluid px-4">
    {{-- Breadcrumb Navigation --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-tachometer-alt me-1"></i>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-chart-line me-1"></i>
                Laporan Penyewaan
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-3" title="Kembali ke Dashboard">
                <i class="fas fa-arrow-left me-1"></i>
                Dashboard
            </a>
            <div>
                <h2 class="h3 mb-1 text-gray-800">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Laporan Penyewaan
                </h2>
                <p class="text-muted mb-0">Analisis dan statistik penyewaan halte</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-info">
                <i class="fas fa-history me-1"></i>
                Riwayat Sewa
            </a>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i>
                    Generate Laporan
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#monthlyReportModal">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Laporan Bulanan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#yearlyReportModal">
                            <i class="fas fa-calendar me-1"></i>
                            Laporan Tahunan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#customReportModal">
                            <i class="fas fa-calendar-day me-1"></i>
                            Laporan Custom
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Filter Year/Month (Auto-submit) --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm" class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="year" class="form-label fw-bold">
                                <i class="fas fa-calendar me-1"></i>
                                Tahun
                            </label>
                            <select name="year" id="year" class="form-select">
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="month" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Bulan
                            </label>
                            <select name="month" id="month" class="form-select">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Overview --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penyewaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $currentMonthStats['total_rentals'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($currentMonthStats['total_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sedang Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $currentMonthStats['active_rentals'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Telah Berakhir
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $currentMonthStats['expired_rentals'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mb-4">
        {{-- Revenue Chart --}}
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pendapatan Bulanan {{ $year }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Haltes --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Halte Terpopuler</h6>
                </div>
                <div class="card-body">
                    @if($topHaltes->count() > 0)
                        @foreach($topHaltes->take(5) as $index => $halte)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 35px; height: 35px; font-size: 0.875rem; font-weight: bold;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ Str::limit($halte->name, 25) }}</div>
                                    <div class="text-muted small">
                                        {{ $halte->rental_count }} penyewaan •
                                        Rp {{ number_format($halte->total_revenue, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p>Belum ada data penyewaan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Statistics Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Statistik Bulanan {{ $year }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Bulan</th>
                            <th>Total Penyewaan</th>
                            <th>Pendapatan</th>
                            <th>Halte Unik</th>
                            <th>Rata-rata per Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenueChart as $data)
                            <tr>
                                <td class="fw-bold">{{ $data['month'] }}</td>
                                <td>{{ $data['rentals'] }}</td>
                                <td>Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $monthData = $monthlyStats->firstWhere('month', array_search($data['month'], ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) + 1);
                                    @endphp
                                    {{ $monthData ? $monthData->unique_haltes : 0 }}
                                </td>
                                <td>
                                    @if($data['rentals'] > 0)
                                        Rp {{ number_format($data['revenue'] / $data['rentals'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr class="fw-bold">
                            <td>TOTAL</td>
                            <td>{{ collect($revenueChart)->sum('rentals') }}</td>
                            <td>Rp {{ number_format(collect($revenueChart)->sum('revenue'), 0, ',', '.') }}</td>
                            <td>{{ $topHaltes->count() }}</td>
                            <td>
                                @php
                                    $totalRentals = collect($revenueChart)->sum('rentals');
                                    $totalRevenue = collect($revenueChart)->sum('revenue');
                                @endphp
                                @if($totalRentals > 0)
                                    Rp {{ number_format($totalRevenue / $totalRentals, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal for Monthly Report --}}
<div class="modal fade" id="monthlyReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reports.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="report_type" value="yearly">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Laporan Tahunan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="yearly_year" class="form-label">Tahun</label>
                        <select name="year" id="yearly_year" class="form-select" required>
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal for Custom Report --}}
<div class="modal fade" id="customReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reports.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="report_type" value="custom">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Laporan Custom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="custom_start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="custom_start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="custom_end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="custom_end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>
@endsection

@push('scripts')
    <script>
        // Pass data to JavaScript
        window.revenueChartData = {!! json_encode($revenueChart) !!};
    </script>
    @vite(['resources/js/admin/reports/index.js'])
@endpush
