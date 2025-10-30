// Dashboard Admin JavaScript
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();

    // Auto refresh every 5 minutes
    setInterval(function() {
        // You can add auto-refresh logic here
        console.log('Auto refresh triggered');
    }, 300000);

    // Run animation when page loads
    setTimeout(animateStats, 500);
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
        // Get data from data attributes if available
        const availableHaltes = statusCtx.dataset.available || 0;
        const rentedHaltes = statusCtx.dataset.rented || 0;

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Disewa', 'Maintenance'],
                datasets: [{
                    data: [availableHaltes, rentedHaltes, 2],
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
}z
