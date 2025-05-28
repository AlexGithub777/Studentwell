<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Your Mood Has Shifted</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$moodRatings30days->isEmpty())
                <!-- line graph of mood over time (14 days)-->
                <canvas style="max-height:275px;" id="moodLineChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No mood data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Mood Distribution</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$moodDistribution->isEmpty())
                <!-- pie chart of mood distribution -->
                <canvas style="max-height:275px;" id="moodDistributionPieChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No mood data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Mood Logging Rate</h5>
                <span> (Last 30 days)</span>
            </div>
            <!-- donut chart of mood logging rate (days logged vs not last 30 days)-->
            <canvas style="max-height:275px;" id="moodLoggingRateDonut"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Emotion Distribution</h5>
                <span> (Top 10 over last 30 days)</span>
            </div>
            @if (!$emotionsDistribution->isEmpty())
                <!-- pie chart of emotions distribution -->
                <canvas style="max-height:275px;" id="emotionPieChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No emotion data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@if (!$moodRatings30days->isEmpty())
    <!-- Mood insights -->
    @php
        $reversedMood30days = $moodRatings30days->sortBy('MoodDate');
        $moodLabels30days = $reversedMood30days
            ->pluck('MoodDate')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $moodScores30days = $reversedMood30days->pluck('MoodRating');
    @endphp
    <!-- Mood Line Chart -->
    <script>
        const moodLabels30days = @json($moodLabels30days);
        const moodScores30days = @json($moodScores30days);

        const moodMap30days = {
            1: '😢 Sad',
            2: '😔 Down',
            3: '😐 Okay',
            4: '😊 Good',
            5: '😄 Great'
        };

        new Chart(document.getElementById("moodLineChart"), {
            type: 'line',
            data: {
                labels: moodLabels30days,
                datasets: [{
                    label: 'Mood Rating',
                    data: moodScores30days,
                    borderColor: '#1e1e76',
                    backgroundColor: '#1e1e76',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const moodText = moodMap30days[value] || value;
                                return `Mood Rating: ${moodText}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Mood Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Mood Rating'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return moodMap30days[value] || value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
<!-- Mood Distribution Pie Chart -->
@if (!$moodDistribution->isEmpty())
    <script>
        const moodsMap = {
            1: '😢 Sad',
            2: '😔 Down',
            3: '😐 Okay',
            4: '😊 Good',
            5: '😄 Great'
        };

        const moodCountsRaw = @json($moodDistribution);

        // Sort moodCounts by count descending
        const moodCounts = moodCountsRaw.sort((a, b) => b.count - a.count);

        // Process the data to create labels and values
        const moodPieLabels = moodCounts.map(item => moodsMap[item.MoodRating]);
        const moodData = moodCounts.map(item => item.count);
        const moodTotal = moodData.reduce((a, b) => a + b, 0);

        // Define colors for each mood (matching the emotional tone)
        const moodColors = {
            1: '#e74c3c', // Red for Sad
            2: '#f39c12', // Orange for Down
            3: '#f1c40f', // Yellow for Okay
            4: '#2ecc71', // Green for Good
            5: '#27ae60' // Darker green for Great
        };

        // Map colors to match the sorted data order
        const backgroundColorsMoods = moodCounts.map(item => moodColors[item.MoodRating]);

        new Chart(document.getElementById("moodDistributionPieChart"), {
            type: 'pie',
            data: {
                labels: moodPieLabels,
                datasets: [{
                    data: moodData,
                    backgroundColor: backgroundColorsMoods,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                var moodLogLabel = value === 1 ? 'log' : 'logs';
                                const percentage = ((value / moodTotal) * 100).toFixed(0);
                                return `${context.label}: ${value} ${moodLogLabel} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        var moodLogLabel = value === 1 ? 'log' : 'logs';
                                        const percentage = ((value / moodTotal) * 100).toFixed(0);
                                        return {
                                            text: `${label}: ${value} ${moodLogLabel} (${percentage}%)`,
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
                            size: 12
                        },
                        formatter: function(value, context) {
                            const percentage = ((value / moodTotal) * 100).toFixed(0);
                            return percentage >= 3 ? percentage + '%' : ''; // Only show if >= 3%
                        }
                    }
                }
            }
        });
    </script>
@endif
<!-- Mood Logging Rate Donut Chart -->
<script>
    const moodLoggingRate = {{ is_numeric($moodLoggingRate) ? $moodLoggingRate : 0 }};
    const loggedDays = {{ $loggedDays }};
    const totalDays = {{ $totalDays }};
    const unloggedDays = totalDays - loggedDays;
    const loggingData = [moodLoggingRate, 100 - moodLoggingRate];

    new Chart(document.getElementById("moodLoggingRateDonut"), {
        type: 'doughnut',
        data: {
            labels: ['Days Logged', 'Days Not Logged'],
            datasets: [{
                data: loggingData,
                backgroundColor: ['#2ecc71', '#e74c3c'] // Green for logged, Red for not logged
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
                            var days = label === 'Days Logged' ? loggedDays : unloggedDays;
                            var daysLabel = days === 1 ? 'day' : 'days';
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
                                    var days = label === 'Days Logged' ? loggedDays :
                                        unloggedDays;
                                    var daysLabel = days === 1 ? 'day' : 'days';
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
                // Register the datalabels plugin inline
                chart.options.plugins.datalabels = chart.options.plugins.datalabels || {};
            }
        }]
    });
</script>
@if (!$emotionsDistribution->isEmpty())
    <!-- Emotion Distribution Pie Chart -->
    <script>
        const emotionsData = @json($emotionsDistribution);
        const emotionLabels = emotionsData.map(item => item.emotion);
        const emotionCounts = emotionsData.map(item => item.count);
        const emotionTotal = emotionCounts.reduce((a, b) => a + b, 0);

        const emotionColors = [
            '#3498db', // blue
            '#9b59b6', // purple
            '#e67e22', // orange
            '#1abc9c', // teal
            '#f39c12', // yellow
            '#c0392b', // red
            '#2ecc71', // green
            '#34495e', // dark blue-grey
            '#e74c3c', // bright red
            '#16a085', // dark teal
            '#8e44ad', // deep purple
        ];

        new Chart(document.getElementById("emotionPieChart"), {
            type: 'pie',
            data: {
                labels: emotionLabels,
                datasets: [{
                    data: emotionCounts,
                    backgroundColor: emotionColors.slice(0, emotionLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                var daysLegendLabel = value === 1 ? 'log' : 'logs';
                                const percentage = ((value / emotionTotal) * 100).toFixed(0);
                                return `${context.label}: ${value} ${daysLegendLabel} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    var daysLegendLabel = value === 1 ? 'log' : 'logs';
                                    const percentage = ((value / emotionTotal) * 100).toFixed(0);
                                    return {
                                        text: `${label}: ${value} ${daysLegendLabel} (${percentage}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].backgroundColor[i],
                                        lineWidth: 0,
                                        index: i
                                    };
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
