<x-card>
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Utility Usage Trends</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="utilityUsageChart"></canvas>
        </div>
    </div>
</x-card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('utilityUsageChart').getContext('2d');
    
    // Chart configuration
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Water Usage',
                    data: @json($waterData),
                    borderColor: '#3B82F6',
                    backgroundColor: '#3B82F640',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Electric Usage',
                    data: @json($electricData),
                    borderColor: '#F59E0B',
                    backgroundColor: '#F59E0B40',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: theme => theme.chart.showGrid ? '#E5E7EB20' : 'transparent'
                    },
                    ticks: {
                        color: theme => theme.chart.textColor
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: theme => theme.chart.textColor
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: theme => theme.chart.textColor
                    }
                }
            }
        }
    });
});
</script>
@endpush
