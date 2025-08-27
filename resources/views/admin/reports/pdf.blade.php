{{-- resources/views/admin/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1a4b8c;
        }

        .header h1 {
            color: #1a4b8c;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }

        .report-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .report-info h3 {
            color: #1a4b8c;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }

        .summary-card h4 {
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #1a4b8c;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background: #1a4b8c;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .data-table tr:hover {
            background: #e3f2fd;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-upcoming {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>DINAS PERHUBUNGAN</h1>
        <h2>{{ $reportTitle }}</h2>
    </div>

    {{-- Report Information --}}
    <div class="report-info">
        <h3>Informasi Laporan</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Tanggal Generate:</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Data:</span>
                <span class="info-value">{{ $rentals->count() }} penyewaan</span>
            </div>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Penyewaan</h4>
            <div class="value">{{ $summary['total_rentals'] }}</div>
        </div>
        <div class="summary-card">
            <h4>Total Pendapatan</h4>
            <div class="value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <h4>Halte Unik</h4>
            <div class="value">{{ $summary['unique_haltes'] }}</div>
        </div>
        <div class="summary-card">
            <h4>Rata-rata Biaya</h4>
            <div class="value">Rp {{ number_format($summary['average_rental_cost'], 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Data Table --}}
    @if($rentals->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Halte</th>
                    <th style="width: 15%">Penyewa</th>
                    <th style="width: 12%">Tanggal Mulai</th>
                    <th style="width: 12%">Tanggal Selesai</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 12%">Biaya Sewa</th>
                    <th style="width: 14%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $index => $rental)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $rental->halte->name }}</strong>
                            @if($rental->halte->address)
                                <br>
                                <small style="color: #666;">{{ Str::limit($rental->halte->address, 40) }}</small>
                            @endif
                        </td>
                        <td>{{ $rental->rented_by }}</td>
                        <td>{{ $rental->rent_start_date->format('d/m/Y') }}</td>
                        <td>{{ $rental->rent_end_date->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @php
                                $now = now();
                                $isActive = $now->between($rental->rent_start_date, $rental->rent_end_date);
                                $isUpcoming = $now->isBefore($rental->rent_start_date);
                            @endphp

                            @if($isActive)
                                <span class="status-badge status-active">Aktif</span>
                            @elseif($isUpcoming)
                                <span class="status-badge status-upcoming">Akan Datang</span>
                            @else
                                <span class="status-badge status-completed">Selesai</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <strong>Rp {{ number_format($rental->rental_cost, 0, ',', '.') }}</strong>
                            <br>
                            <small style="color: #666;">
                                {{ $rental->rent_start_date->diffInDays($rental->rent_end_date) }} hari
                            </small>
                        </td>
                        <td>
                            @if($rental->notes)
                                {{ Str::limit($rental->notes, 50) }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f1f3f4; font-weight: bold;">
                    <td colspan="6" class="text-center">TOTAL</td>
                    <td class="text-right">
                        Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                    </td>
                    <td>{{ $summary['total_rentals'] }} penyewaan</td>
                </tr>
            </tfoot>
        </table>

        {{-- Additional Statistics (if more than 10 records, show top stats) --}}
        @if($rentals->count() > 10)
            <div class="page-break"></div>

            <h3 style="color: #1a4b8c; margin-bottom: 20px;">Statistik Tambahan</h3>

            {{-- Top Haltes --}}
            <div style="margin-bottom: 30px;">
                <h4 style="color: #666; margin-bottom: 15px;">Halte Terpopuler</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Halte</th>
                            <th>Jumlah Sewa</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $topHaltes = $rentals->groupBy('halte_id')
                                ->map(function($group) {
                                    return [
                                        'halte' => $group->first()->halte,
                                        'count' => $group->count(),
                                        'revenue' => $group->sum('rental_cost')
                                    ];
                                })
                                ->sortByDesc('count')
                                ->take(5);
                        @endphp

                        @foreach($topHaltes as $index => $data)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $data['halte']->name }}</td>
                                <td class="text-center">{{ $data['count'] }}</td>
                                <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Monthly Breakdown --}}
            <div style="margin-bottom: 30px;">
                <h4 style="color: #666; margin-bottom: 15px;">Breakdown per Bulan</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Jumlah Sewa</th>
                            <th>Total Pendapatan</th>
                            <th>Rata-rata per Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $monthlyData = $rentals->groupBy(function($rental) {
                                return $rental->rent_start_date->format('Y-m');
                            })->map(function($group, $month) {
                                return [
                                    'month' => \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                                    'count' => $group->count(),
                                    'revenue' => $group->sum('rental_cost'),
                                    'average' => $group->count() > 0 ? $group->sum('rental_cost') / $group->count() : 0
                                ];
                            });
                        @endphp

                        @foreach($monthlyData as $data)
                            <tr>
                                <td>{{ $data['month'] }}</td>
                                <td class="text-center">{{ $data['count'] }}</td>
                                <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($data['average'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 50px; color: #666;">
            <h3>Tidak Ada Data</h3>
            <p>Tidak ditemukan data penyewaan untuk periode yang dipilih.</p>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Manajemen Halte</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    {{-- Print Button (hidden when printed) --}}
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()"
                style="background: #1a4b8c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <button onclick="window.history.back()"
                style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
