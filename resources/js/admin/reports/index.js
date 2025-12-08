import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const filterForm = document.getElementById('filterForm');
    const loadingOverlay = document.getElementById('loadingOverlay');

    function submitFormWithLoading() {
        loadingOverlay.classList.add('active');
        filterForm.submit();
    }

    if (yearSelect) {
        yearSelect.addEventListener('change', submitFormWithLoading);
    }

    if (monthSelect) {
        monthSelect.addEventListener('change', submitFormWithLoading);
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueChartData = window.revenueChartData || [];

        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueChartData.map(item => item.month),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueChartData.map(item => item.revenue),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }, {
                    label: 'Jumlah Penyewaan',
                    data: revenueChartData.map(item => item.rentals),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                } else {
                                    label += context.parsed.y + ' penyewaan';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pendapatan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah Penyewaan'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // Auto-set max date for custom report
    document.getElementById('custom_start_date')?.addEventListener('change', function() {
        const endDateInput = document.getElementById('custom_end_date');
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    document.getElementById('custom_end_date')?.addEventListener('change', function() {
        const startDateInput = document.getElementById('custom_start_date');
        startDateInput.max = this.value;
        if (startDateInput.value && startDateInput.value > this.value) {
            startDateInput.value = this.value;
        }
    });

    // Set default dates for custom report (current month)
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    const startDateInput = document.getElementById('custom_start_date');
    const endDateInput = document.getElementById('custom_end_date');

    if (startDateInput && endDateInput) {
        startDateInput.value = firstDay.toISOString().split('T')[0];
        endDateInput.value = lastDay.toISOString().split('T')[0];
    }
});
