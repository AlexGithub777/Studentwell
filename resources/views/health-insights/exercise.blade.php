<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Daily Exercise Duration</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$exerciseLogs->isEmpty())
                <!-- line graph of exercise duration over time (30 days) -->
                <canvas style="max-height:275px;" id="exerciseLineChart"></canvas>
            @else
                <div class="text-center text-muted">
                    <p>No exercise data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Weekly Exercise Duration</h5>
                <span> (Last 4 weeks)</span>
            </div>
            @if (!$exerciseLogsLast4Weeks->isEmpty())
                <!-- bar graph of weekly exercise duration (last 4 weeks) -->
                <canvas style="max-height:275px;" id="exerciseBarChart"></canvas>
            @else
                <div class="text-center text-muted">
                    <p>No exercise data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Exercise Completion Rate</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!empty($exerciseChartData))
                <!-- Donut chart of exercise completion rate -->
                <canvas style="max-height:275px;" id="exerciseDonutChart"></canvas>
            @else
                <div class="text-center text-muted">
                    <p>No exercise data available.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Exercise Type Breakdown</h5>
                <span> (Last 30 days)</span>
            </div>
            @if (!$exerciseLogs->isEmpty())
                <!-- Pie chart of exercise types (Running, Boxing, Football) -->
                <canvas style="max-height:275px;" id="exercisePieChart"></canvas>
            @else
                <div class="text-center text-muted">
                    <p>No exercise data available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@if (!$exerciseLogs->isEmpty())
    <!-- Exercise insights -->
    @php
        $reversedExercise = $exerciseLogs->sortBy('ExerciseDateTime');
        $exerciseLabels = $reversedExercise
            ->pluck('ExerciseDateTime')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $exerciseDurations = $reversedExercise->pluck('DurationMinutes');
    @endphp
    <!-- Bar graph of daily exercise duration -->
    <script>
        const exerciseLabels = @json($exerciseLabels);
        const exerciseDurations = @json($exerciseDurations);

        new Chart(document.getElementById("exerciseLineChart"), {
            type: 'line',
            data: {
                labels: exerciseLabels,
                datasets: [{
                    label: 'Duration',
                    data: exerciseDurations,
                    borderColor: '#1e1e76',
                    backgroundColor: '#1e1e76',
                    fill: false,
                    tension: 0.1, // smooth line
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Exercise Log Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Exercise Duration (mins)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // context.dataset.label is 'Duration'
                                // context.parsed.y is the data value (duration in mins)
                                return `Duration: ${context.parsed.y} minutes`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
@if (!$exerciseLogsLast4Weeks->isEmpty())
    <!-- Bar graph of weekly exercise duration -->
    <script>
        const weekLabels = @json($weekLabels);
        const durations = @json($durations);

        new Chart(document.getElementById('exerciseBarChart'), {
            type: 'bar',
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Total Duration (mins)',
                    data: durations,
                    backgroundColor: barBackgroundColors,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Duration (minutes)'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Week Starting Date'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Duration: ${context.parsed.x} minutes`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endif
@if (!empty($exerciseChartData))
    <!-- Donut chart for exercise completion status -->
    <script>
        const chartData = @json($exerciseChartData);

        const exerciseDonutlabels = chartData.map(entry => `${entry.status}: ${entry.count} logs (${entry.percentage}%)`);
        const exerciseDonutData = chartData.map(entry => entry.count);
        const percentages = chartData.map(entry => entry.percentage);
        const statuses = chartData.map(entry => entry.status);

        const exerciseDonutBackgroundColours = {
            'Completed': '#2ecc71', // Green
            'Missed': '#e74c3c', // Red
            'Partially': '#f1c40f' // Yellow
        };

        new Chart(document.getElementById('exerciseDonutChart'), {
            type: 'doughnut',
            data: {
                labels: exerciseDonutlabels,
                datasets: [{
                    data: exerciseDonutData,
                    backgroundColor: statuses.map(status => exerciseDonutBackgroundColours[status] ||
                        'rgba(201, 203, 207, 1)'),
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return statuses[index]; // Just the status in the header
                            },
                            label: function(context) {
                                const index = context.dataIndex;
                                const status = statuses[index];
                                const count = exerciseDonutData[index];
                                const percentage = percentages[index];
                                return `${status}: ${count} log${count !== 1 ? 's' : ''} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
@if (!$exerciseLogs->isEmpty())
    <!-- Pie chart for exercise types -->
    <script>
        const exerciseTypeEmojis = {
            'Basketball': '🏀',
            'Boxing': '🥊',
            'Climbing': '🧗',
            'Cycling': '🚴',
            'Dance': '💃',
            'Football': '⚽',
            'Hiking': '🥾',
            'Running': '🏃',
            'Skating': '⛸️',
            'Skiing': '🎿',
            'Sports': '🥇',
            'Swimming': '🏊',
            'Tennis': '🎾',
            'Volleyball': '🏐',
            'Walking': '🚶',
            'Weight Lifting': '🏋️',
            'Yoga': '🧘',
            'Other': '❓'
        };

        const exerciseTypesData = @json($exerciseTypesDistribution);

        const rawLabels = Object.keys(exerciseTypesData);
        const rawExerciseData = Object.values(exerciseTypesData);
        const totalLogs = rawExerciseData.reduce((sum, val) => sum + val, 0);

        const exerciseTypeBackgroundColours = [
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


        // Combine label and count
        let exerciseData = rawLabels.map((label, i) => ({
            label: label,
            count: rawExerciseData[i]
        }));

        // Sort descending by count
        exerciseData.sort((a, b) => b.count - a.count);

        // Build formatted data
        const sortedLabels = exerciseData.map(item => {
            const percentage = totalLogs > 0 ? ((item.count / totalLogs) * 100).toFixed(1) : 0;
            return `${item.label}: ${item.count} log${item.count !== 1 ? 's' : ''} (${percentage}%)`;
        });

        const sortedData = exerciseData.map(item => item.count);
        const sortedRawLabels = exerciseData.map(item => {
            const emoji = exerciseTypeEmojis[item.label] || '';
            return `${emoji} ${item.label}`;
        });


        new Chart(document.getElementById('exercisePieChart'), {
            type: 'pie',
            data: {
                labels: sortedRawLabels,
                datasets: [{
                    data: sortedData,
                    backgroundColor: sortedData.map((_, i) => exerciseTypeBackgroundColours[i %
                        exerciseTypeBackgroundColours
                        .length]),
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                animation: {
                    animateScale: true, // Enables scaling animation
                    animateRotate: true // Optional: animates rotation from 0 to full
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return sortedRawLabels[index]; // Show only the exercise type in the header
                            },
                            label: function(context) {
                                const index = context.dataIndex;
                                const label = sortedRawLabels[index];
                                const value = sortedData[index];
                                const percentage = totalLogs > 0 ? ((value / totalLogs) * 100).toFixed(1) : 0;
                                return `${label}: ${value} log${value !== 1 ? 's' : ''} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
