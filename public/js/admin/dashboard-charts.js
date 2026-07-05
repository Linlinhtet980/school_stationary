document.addEventListener('DOMContentLoaded', function() {
    
    // Check if chartData is defined (passed from blade)
    if (typeof chartData === 'undefined') {
        console.error('chartData is not defined.');
        return;
    }

    // --- 1. Sales Performance Line Chart ---
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: chartData.salesLabels,
                datasets: [
                    {
                        label: 'Total Sales (Ks)',
                        data: chartData.salesData,
                        borderColor: '#3182CE',
                        backgroundColor: 'rgba(49, 130, 206, 0.2)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Order Count',
                        data: chartData.ordersData,
                        borderColor: '#48BB78',
                        backgroundColor: 'rgba(72, 187, 120, 0.2)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false // We already have a custom HTML legend in the design
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 10,
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat('en-US').format(context.parsed.y) + ' Ks';
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { borderDash: [2, 4], color: '#e2e8f0' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
                                return value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { display: false },
                        min: 0,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // --- 2. Sales by Category Doughnut Chart ---
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        
        // Handle empty data case
        const hasData = chartData.categoryData && chartData.categoryData.length > 0;
        const labels = hasData ? chartData.categoryLabels : ['No Data'];
        const data = hasData ? chartData.categoryData : [1];
        const bgColors = hasData 
            ? ['#3182CE', '#48BB78', '#FFD200', '#ED8936', '#805AD5', '#E53E3E', '#38B2AC', '#A0AEC0'] 
            : ['#e2e8f0'];

        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: bgColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (!hasData) return 'No sales yet';
                                const value = context.parsed;
                                return ' ' + new Intl.NumberFormat('en-US').format(value) + ' Ks';
                            }
                        }
                    }
                }
            }
        });
    }
});
