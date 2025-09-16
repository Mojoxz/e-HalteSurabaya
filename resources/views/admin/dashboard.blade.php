@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card dishub-card-primary">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Total Halte</div>
                <div class="stat-value">{{ $totalHaltes }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="text-success">+2 bulan ini</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-bus"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-success">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Tersedia</div>
                <div class="stat-value">{{ $availableHaltes }}</div>
                <div class="stat-trend">
                    <span class="text-muted">{{ round(($availableHaltes/$totalHaltes)*100, 1) }}% dari total</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-warning">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Disewa</div>
                <div class="stat-value">{{ $rentedHaltes }}</div>
                <div class="stat-trend">
                    <span class="text-muted">{{ round(($rentedHaltes/$totalHaltes)*100, 1) }}% dari total</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card dishub-card-accent">
        <div class="stat-card-body">
            <div class="stat-info">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="text-success">+15% bulan ini</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="analytics-grid">
    <!-- Rental Chart -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-chart-line"></i>
                Statistik Penyewaan (30 Hari Terakhir)
            </h5>
            <div class="analytics-actions">
                <select class="form-select form-select-sm">
                    <option value="30">30 Hari</option>
                    <option value="60">60 Hari</option>
                    <option value="90">90 Hari</option>
                </select>
            </div>
        </div>
        <div class="analytics-body">
            <canvas id="rentalChart" height="300"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-chart-pie"></i>
                Distribusi Status Halte
            </h5>
        </div>
        <div class="analytics-body">
            <canvas id="statusChart" height="300"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-clock"></i>
                Aktivitas Terbaru
            </h5>
        </div>
        <div class="analytics-body">
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon bg-success">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Halte Baru Ditambahkan</div>
                        <div class="activity-desc">Halte Suramadu berhasil ditambahkan</div>
                        <div class="activity-time">2 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-warning">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Penyewaan Baru</div>
                        <div class="activity-desc">Halte Taman Bungkul disewa oleh PT ABC</div>
                        <div class="activity-time">4 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">User Baru</div>
                        <div class="activity-desc">John Doe mendaftar sebagai user</div>
                        <div class="activity-time">6 jam yang lalu</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon bg-primary">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Data Halte Diperbarui</div>
                        <div class="activity-desc">Informasi Halte Wonokromo diperbarui</div>
                        <div class="activity-time">8 jam yang lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">
                <i class="fas fa-users"></i>
                Statistik User
            </h5>
        </div>
        <div class="analytics-body">
            <div class="user-stats">
                <div class="user-stat-item">
                    <div class="user-stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::count() }}</div>
                        <div class="user-stat-label">Total User</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::active()->count() }}</div>
                        <div class="user-stat-label">User Aktif</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-warning">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::admins()->count() }}</div>
                        <div class="user-stat-label">Admin</div>
                    </div>
                </div>

                <div class="user-stat-item">
                    <div class="user-stat-icon bg-secondary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-stat-info">
                        <div class="user-stat-value">{{ \App\Models\User::users()->count() }}</div>
                        <div class="user-stat-label">Regular User</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard specific styles - integrated with admin layout */
:root {
    --dishub-blue: #1a4b8c;
    --dishub-light-blue: #e6f0fa;
    --dishub-accent: #2a75d6;
    --dishub-dark-blue: #153a73;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border-left: 4px solid var(--dishub-blue);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(26, 75, 140, 0.1), rgba(26, 75, 140, 0.05));
    border-radius: 0 0 0 60px;
}

.stat-card.dishub-card-success {
    border-left-color: #10b981;
}

.stat-card.dishub-card-success::before {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
}

.stat-card.dishub-card-warning {
    border-left-color: #f59e0b;
}

.stat-card.dishub-card-warning::before {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
}

.stat-card.dishub-card-accent {
    border-left-color: var(--dishub-accent);
}

.stat-card.dishub-card-accent::before {
    background: linear-gradient(135deg, rgba(42, 117, 214, 0.1), rgba(42, 117, 214, 0.05));
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.stat-card-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stat-trend {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.stat-icon {
    font-size: 2.5rem;
    color: var(--dishub-blue);
    opacity: 0.2;
    margin-left: 1rem;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.analytics-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.analytics-header {
    background: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
    color: white;
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.analytics-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.analytics-actions .form-select {
    background-color: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.analytics-actions .form-select:focus {
    background-color: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: none;
}

.analytics-actions .form-select option {
    background: var(--dishub-blue);
    color: white;
}

.analytics-body {
    padding: 1.5rem;
}

/* Activity List */
.activity-list {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.activity-list::-webkit-scrollbar {
    width: 6px;
}

.activity-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: #f8fafc;
    border-radius: 8px;
    margin: 0 -0.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 0.875rem;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.activity-desc {
    color: #64748b;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.activity-time {
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 500;
}

/* User Stats */
.user-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
}

.user-stat-item {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.user-stat-item:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.user-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 1rem;
}

.user-stat-info {
    flex: 1;
    min-width: 0;
}

.user-stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.user-stat-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Chart containers */
#rentalChart,
#statusChart {
    max-width: 100%;
    height: 300px !important;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card,
.analytics-card {
    animation: fadeInUp 0.6s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

.analytics-card:nth-child(1) { animation-delay: 0.5s; }
.analytics-card:nth-child(2) { animation-delay: 0.6s; }
.analytics-card:nth-child(3) { animation-delay: 0.7s; }
.analytics-card:nth-child(4) { animation-delay: 0.8s; }

/* Responsive Design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .stat-value {
        font-size: 1.875rem;
    }

    .analytics-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .analytics-actions {
        width: 100%;
    }

    .analytics-actions .form-select {
        width: 100%;
    }

    .user-stats {
        grid-template-columns: 1fr;
    }

    .user-stat-item {
        padding: 1rem;
    }

    .user-stat-value {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .stat-card-body {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .stat-icon {
        margin-left: 0;
        margin-top: 0.5rem;
    }

    .activity-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .activity-icon {
        margin-right: 0;
    }
}
</style>
@endpush

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();

    // Auto refresh every 5 minutes
    setInterval(function() {
        // You can add auto-refresh logic here
        console.log('Auto refresh triggered');
    }, 300000);
});

function initializeCharts() {
    // Rental Chart
    const rentalCtx = document.getElementById('rentalChart');
    if (rentalCtx) {
        new Chart(rentalCtx, {
            type: 'line',
            data: {
                labels: ['1 Jan', '5 Jan', '10 Jan', '15 Jan', '20 Jan', '25 Jan', '30 Jan'],
                datasets: [{
                    label: 'Penyewaan Baru',
                    data: [12, 19, 3, 5, 2, 3, 9],
                    borderColor: '#1a4b8c',
                    backgroundColor: 'rgba(26, 75, 140, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#1a4b8c',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 75, 140, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#1a4b8c',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Disewa', 'Maintenance'],
                datasets: [{
                    data: [{{ $availableHaltes }}, {{ $rentedHaltes }}, 2],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#1e293b',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
}

// Add smooth scroll animation for stats
function animateStats() {
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const finalValue = parseInt(stat.textContent.replace(/[^\d]/g, ''));
        let currentValue = 0;
        const increment = finalValue / 50;
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            
            // Format number with thousand separator for Indonesian
            const formattedValue = Math.floor(currentValue).toLocaleString('id-ID');
            
            // Preserve currency symbol if exists
            if (stat.textContent.includes('Rp')) {
                stat.textContent = `Rp ${formattedValue}`;
            } else {
                stat.textContent = formattedValue;
            }
        }, 50);
    });
}

// Run animation when page loads
setTimeout(animateStats, 500);
</script>
@endpush