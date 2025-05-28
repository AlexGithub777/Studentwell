<!-- Sleep insights -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Sleep Duration</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$sleepLogs->isEmpty())
                <!-- line graph of sleep duration over time (30 days) -->
                <canvas style="max-height:275px;" id="sleepDurationLineChart"></canvas>
            @else
                <div class="text-center">
                    <p class="text-muted">No sleep data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Your Sleep Schedule</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$sleepLogs->isEmpty())
                <!-- line graph of bedtime and wake-up time over time (30 days) use $sleepLogs -->
                <canvas style="max-height:275px;" id="sleepScheduleLineChart"></canvas>
            @else
                <div class="text-center">
                    <p class="text-muted">No sleep data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Sleep Logging Rate</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- donut chart of sleep logging rate (if sleep is Logged or not logged in the last 30 days) -->
            <canvas style="max-height:275px;" id="sleepLogRateDonutChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Sleep Quality Breakdown</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$sleepQualityDistribution->isEmpty())
                <!-- pie chart of sleep quality distribution (1, 2, 3) -->
                <canvas style="max-height:275px;" id="sleepPieChart"></canvas>
            @else
                <div class="text-center">
                    <p class="text-muted">No sleep data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@if (!$sleepLogs->isEmpty())
    @php
        $sleepLogs = $sleepLogs->map(function ($log) {
            return [
                'SleepDate' => $log->SleepDate->format('M d'),
                'SleepDurationMinutes' => $log->SleepDurationMinutes,
                'BedTime' => $log->BedTime->format('H:i'),
                'WakeTime' => $log->WakeTime->format('H:i'),
            ];
        });
    @endphp
    <script>
        const sleepLogs = @json($sleepLogs);

        // Prepare data for the line chart
        const sleepDates = sleepLogs.map(log => log.SleepDate);
        const sleepDurations = sleepLogs.map(log => (log.SleepDurationMinutes / 60).toFixed(2)); // convert minutes to hours

        new Chart(document.getElementById("sleepDurationLineChart"), {
            type: 'line',
            data: {
                labels: sleepDates,
                datasets: [{
                    label: 'Sleep Duration (hours)',
                    data: sleepDurations,
                    borderColor: '#1e1e76',
                    backgroundColor: '#1e1e7633',
                    tension: 0.1, // smooth line
                    fill: true,
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} hours`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        labels: {
                            color: '#555'
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Sleep Log Date'
                        },
                        ticks: {
                            maxTicksLimit: 10,
                            callback: function(value, index, ticks) {
                                return sleepDates[index]; // keep original date string
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Hours Slept'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endif
@if (!$sleepLogs->isEmpty())
    <script>
        const sleepTimeLogs = @json($sleepLogs);

        const parseTimeToMinutes = (timeStr) => {
            if (!timeStr || typeof timeStr !== 'string') return null; // <-- safe fallback
            const parts = timeStr.split(':');
            if (parts.length < 2) return null;
            const hours = parseInt(parts[0], 10);
            const minutes = parseInt(parts[1], 10);
            return hours * 60 + minutes;
        };


        const formatMinutesToTime = (minutes) => {
            const h = Math.floor(minutes / 60).toString().padStart(2, '0');
            const m = (minutes % 60).toString().padStart(2, '0');
            return `${h}:${m}`;
        };

        const sleepTimeLabels = sleepTimeLogs.map(log => log.SleepDate);
        const bedTimes = sleepTimeLogs.map((log, i) => {
            if (!log.BedTime) console.warn(`Missing BedTime at index ${i}`, log);
            return parseTimeToMinutes(log.BedTime);
        });

        const wakeTimes = sleepTimeLogs.map(log => parseTimeToMinutes(log.WakeTime));

        new Chart(document.getElementById('sleepScheduleLineChart'), {
            type: 'line',
            data: {
                labels: sleepTimeLabels,
                datasets: [{
                        label: 'Bed Time',
                        data: bedTimes,
                        borderColor: '#1e1e76',
                        backgroundColor: '#2980b933', // fill color
                        fill: '+1', // fill to the next dataset
                        tension: 0.1
                    },
                    {
                        label: 'Wake Time',
                        data: wakeTimes,
                        borderColor: '#27ae60',
                        backgroundColor: '#27ae6033', // fill color
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Time of Day (HH:MM)'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatMinutesToTime(value);
                            },
                            stepSize: 60,
                            min: 0,
                            max: 1440
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const time = formatMinutesToTime(value);
                                return `${context.dataset.label}: ${time}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
<!-- Donut chart for sleep logging rate -->
<script>
    const sleepLoggingRate = {{ is_numeric($sleepLoggingRate) ? $sleepLoggingRate : 0 }};
    const sleepDaysLogged = {{ $sleepDaysLogged }};
    const totalSleepDays = {{ $totalSleepDays }};
    const sleepDaysUnlogged = totalSleepDays - sleepDaysLogged;
    const sleepLoggingData = [sleepLoggingRate, 100 - sleepLoggingRate];

    new Chart(document.getElementById("sleepLogRateDonutChart"), {
        type: 'doughnut',
        data: {
            labels: ['Days Logged', 'Days Not Logged'],
            datasets: [{
                data: sleepLoggingData,
                backgroundColor: ['#2ecc71', '#e74c3c'] // Green = logged, Red = not logged
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const label = context.label;
                            const days = label === 'Days Logged' ? sleepDaysLogged : sleepDaysUnlogged;
                            const daysLabel = days === 1 ? 'day' : 'days';
                            return `${label}: ${days} ${daysLabel} (${value.toFixed(1)}%)`;
                        }
                    }
                },
                legend: {
                    labels: {
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const days = label === 'Days Logged' ? sleepDaysLogged :
                                        sleepDaysUnlogged;
                                    const daysLabel = days === 1 ? 'day' : 'days';
                                    return {
                                        text: `${label}: ${days} ${daysLabel} (${value.toFixed(1)}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].backgroundColor[i],
                                        lineWidth: 0,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: 'white',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: function(value, context) {
                        return value.toFixed(1) + '%';
                    }
                }
            }
        },
        plugins: [{
            beforeInit: function(chart) {
                chart.options.plugins.datalabels = chart.options.plugins.datalabels || {};
            }
        }]
    });
</script>
@if (!$sleepQualityDistribution->isEmpty())
    <script>
        const sleepQualityDistribution = @json($sleepQualityDistribution);

        // Emoji + Label map for sleep quality
        const sleepQualityMap = {
            1: {
                emoji: '😣',
                label: 'Very Poor',
                color: '#e74c3c'
            },
            2: {
                emoji: '😩',
                label: 'Poor',
                color: '#f39c12'
            },
            3: {
                emoji: '😐',
                label: 'Fair',
                color: '#f1c40f'
            },
            4: {
                emoji: '😊',
                label: 'Good',
                color: '#2ecc71'
            },
            5: {
                emoji: '😴',
                label: 'Excellent',
                color: '#27ae60'
            }
        };

        // Build and sort chart data
        let combined = [];

        Object.keys(sleepQualityMap).forEach(key => {
            const count = sleepQualityDistribution[key] || 0;
            if (count > 0) {
                const item = sleepQualityMap[key];
                combined.push({
                    label: `${item.emoji} ${item.label}`,
                    value: count,
                    color: item.color
                });
            }
        });

        // Sort descending by count (value)
        combined.sort((a, b) => b.value - a.value);

        // Extract sorted arrays
        const qualityLabels = combined.map(item => item.label);
        const qualityData = combined.map(item => item.value);
        const qualityColors = combined.map(item => item.color);
        const totalEntries = qualityData.reduce((a, b) => a + b, 0);

        new Chart(document.getElementById("sleepPieChart"), {
            type: 'pie',
            data: {
                labels: qualityLabels,
                datasets: [{
                    data: qualityData,
                    backgroundColor: qualityColors
                }]
            },
            options: {
                animation: {
                    animateScale: true, // Enables scaling animation
                    animateRotate: true // Optional: animates rotation from 0 to full
                },
                responsive: true,
                animation: {
                    animateScale: true, // Enables scaling animation
                    animateRotate: true // Optional: animates rotation from 0 to full
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const label = context.label;
                                const percent = totalEntries > 0 ? ((value / totalEntries) * 100).toFixed(1) :
                                    0;
                                const entryLabel = value === 1 ? 'entry' : 'entries';
                                return `${label}: ${value} ${entryLabel} (${percent}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            const percent = totalEntries > 0 ? ((value / totalEntries) * 100).toFixed(1) : 0;
                            return percent + '%';
                        }
                    }
                }
            },
            plugins: [{
                beforeInit: function(chart) {
                    chart.options.plugins.datalabels = chart.options.plugins.datalabels || {};
                }
            }]
        });
    </script>
@endif
