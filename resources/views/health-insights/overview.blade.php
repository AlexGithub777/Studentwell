<!-- Overview content -->
<div class="row">
    <div class="col-lg-6">
        <div class="custom-card mb-3 mb-lg-0">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Your Weekly Wellness Activity</h5>
                <span> (log counts across features)</span>
            </div>
            @php
                $totalLogs = array_sum($rawLogData);
            @endphp

            @if ($totalLogs > 0)
                <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                <canvas style="max-height:275px;" id="logsBarChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No log data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="custom-card mb-3 mb-lg-0">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Consistent Your Sleep Has Been</h5>
                <span> (daily hours of sleep)</span>
            </div>
            @php
                $totalSleepLogs = array_sum($sleepConsistency);
            @endphp

            @if ($totalSleepLogs > 0)
                <!-- Donut graph of sleep consistency (daily hours of sleep) -->
                <canvas style="max-height:275px;" id="sleepDonutChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No sleep data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row mt-0 mt-lg-4">
    <div class="col-lg-6">
        <div class="custom-card mb-3 mb-lg-0">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">How Your Mood Has Shifted</h5>
                <span> (14-day trend)</span>
            </div>
            @if (!$moodRatings->isEmpty())
                <!-- Line graph of mood over time (14 days) -->
                <canvas style="max-height:275px;" id="overviewMoodLineChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No mood data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="custom-card mb-3 mb-lg-0">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Where Your Goals Are Focused</h5>
                <span> (goals by category)</span>
            </div>
            @if (!$goalsByCategory->isEmpty())
                <!-- Pie chart of goals by category (exercise, sleep, mood) -->
                <canvas style="max-height:275px;" id="goalsPieChart"></canvas>
            @else
                <div class="text-center text-muted py-5">
                    <p class="mb-0">No goals data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    const barBackgroundColors = [
        'rgba(255, 99, 132, 1)', // Red
        'rgba(54, 162, 235, 1)', // Blue
        'rgba(255, 206, 86, 1)', // Yellow
        'rgba(75, 192, 192, 1)' // Green
    ];
</script>
<!-- Bar Chart for Logs -->
@if ($totalLogs > 0)
    <script>
        const rawLogData = @json($rawLogData);

        const rawData = Object.entries(rawLogData).map(([label, value]) => ({
            label,
            value
        }));

        // Sort descending by value
        rawData.sort((a, b) => b.value - a.value);

        const barLabels = rawData.map(item =>
            item.label.charAt(0).toUpperCase() + item.label.slice(1)
        );

        const barData = rawData.map(item => item.value);

        new Chart(document.getElementById("logsBarChart"), {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Logs Count',
                    data: barData,
                    backgroundColor: barBackgroundColors,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Log Categories'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Logs'
                        },
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
@endif
@if (!$moodRatings->isEmpty())
    <!-- Mood Line Chart -->
    @php
        $reversedMood = $moodRatings->sortBy('MoodDate');
        $moodLabels = $reversedMood->pluck('MoodDate')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $moodScores = $reversedMood->pluck('MoodRating');
    @endphp
    <script>
        const moodLabels = @json($moodLabels);
        const moodScores = @json($moodScores);

        const moodMap = {
            1: '😢 Sad',
            2: '😔 Down',
            3: '😐 Okay',
            4: '😊 Good',
            5: '😄 Great'
        };

        new Chart(document.getElementById("overviewMoodLineChart"), {
            type: 'line',
            data: {
                labels: moodLabels,
                datasets: [{
                    label: 'Mood Rating',
                    data: moodScores,
                    borderColor: '#1e1e76',
                    backgroundColor: '#1e1e76',
                    fill: false,
                    tension: 0.1, // smooth line
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const moodText = moodMap[value] || value;
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
                                return moodMap[value] || value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
@if ($totalSleepLogs > 0)
    <!-- Sleep Donut Chart -->
    <script>
        const sleepRawData = @json(array_values($sleepConsistency));
        const sleepLabelsRaw = ['<6 hrs', '6–8 hrs', '8+ hrs'];
        const sleepColorsRaw = ['#e74c3c', '#f1c40f', '#2ecc71'];

        // Combine and sort descending by value
        const sleepCombined = sleepRawData.map((val, i) => ({
            label: sleepLabelsRaw[i],
            value: val,
            color: sleepColorsRaw[i]
        })).sort((a, b) => b.value - a.value);

        const sleepLabels = sleepCombined.map(item => item.label);
        const sleepData = sleepCombined.map(item => item.value);
        const sleepColors = sleepCombined.map(item => item.color);
        const sleepTotal = sleepData.reduce((a, b) => a + b, 0);

        new Chart(document.getElementById("sleepDonutChart"), {
            type: 'doughnut',
            data: {
                labels: sleepLabels,
                datasets: [{
                    data: sleepData,
                    backgroundColor: sleepColors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const percentage = ((value / sleepTotal) * 100).toFixed(0);
                                return `${context.label}: ${value} log${value !== 1 ? 's' : ''} (${percentage}%)`;
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
                                        const percentage = ((value / sleepTotal) * 100).toFixed(0);
                                        return {
                                            text: `${label}: ${value} log${value !== 1 ? 's' : ''} (${percentage}%)`,
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
                        formatter: function(value) {
                            const percentage = ((value / sleepTotal) * 100).toFixed(0);
                            return percentage + '%';
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
@if (!$goalsByCategory->isEmpty())
    @php
        $orderedGoals = $goalsByCategory->sortByDesc('count');
        $goalLabels = $orderedGoals->pluck('GoalCategory');
        $goalData = $orderedGoals->pluck('count');
        $goalTotal = $goalData->sum();

        $goalCategories = [
            'Academic' => '📘',
            'Career' => '💼',
            'Finance' => '🐖',
            'Hobbies' => '🎨',
            'Mental Health' => '🧠',
            'Nutrition' => '🍎',
            'Physical Health' => '🏋️',
            'Productivity' => '📋',
            'Sleep' => '🛏️',
            'Social' => '👥',
            'Spiritual' => '🙏',
            'Travel' => '✈️',
            'Wellness' => '❤️',
            'Other' => '❓',
        ];
    @endphp

    <!-- Goals Pie Chart -->
    <script>
        const labels = @json($goalLabels);
        const data = @json($goalData);
        const goalTotal = data.reduce((a, b) => a + b, 0);
        const categoryEmojis = @json($goalCategories);

        const colorPalette = [
            '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6',
            '#1abc9c', '#e67e22', '#34495e', '#27ae60', '#8e44ad',
            '#d35400', '#c0392b', '#16a085', '#2980b9', '#7f8c8d',
            '#f1c40f', '#e91e63', '#00bcd4', '#4caf50', '#ff5722'
        ];

        const backgroundColors = labels.map((_, index) => colorPalette[index % colorPalette.length]);

        new Chart(document.getElementById("goalsPieChart"), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                }]
            },
            options: {
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label;
                                const emoji = categoryEmojis[label] || '';
                                const value = context.raw;
                                const logLabel = value === 1 ? 'log' : 'logs';
                                const percent = ((value / goalTotal) * 100).toFixed(0);
                                return `${emoji} ${label}: ${value} ${logLabel} (${percent}%)`;
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
                                        const emoji = categoryEmojis[label] || '';
                                        return {
                                            text: `${emoji} ${label}`,
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
                            const percentage = ((value / goalTotal) * 100).toFixed(0);
                            return percentage >= 5 ? percentage + '%' : '';
                        }
                    }
                }
            }
        });
    </script>
@endif
