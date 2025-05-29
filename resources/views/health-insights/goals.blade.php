<!-- Overview content -->
<div class="row">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Goals Completion Rate Over Time</h5>
                <span> (Last 6 weeks)</span>
            </div>
            @if (!$weeklyCompletionRates->isEmpty())
                <!-- line graph of goals completion rate (last 6 weeks)) -->
                <canvas style="max-height:275px;" id="goalsLineChart"></canvas>
            @else
                <div class="text-center text-muted p-4">
                    <p class="mb-0">No goals logged yet.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Completed Goals by Category</h5>
                <span> (all time)</span>
            </div>
            @if (!$completedGoalsByCategory->isEmpty())
                <!-- bar graph of goals completed by category-->
                <canvas style="max-height:275px;" id="goalsBarChart"></canvas>
            @else
                <div class="text-center text-muted p-4">
                    <p class="mb-0">No goals logged yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row mt-0 mt-md-4">
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Goal Completion Status</h5>
                <span> (all time)</span>
            </div>
            @if (!$fullGoalStatusCounts->isEmpty())
                <!-- donut graph of goal completion status-->
                <canvas style="max-height:275px;" id="goallsDonutChart"></canvas>
            @else
                <div class="text-center text-muted p-4">
                    <p class="mb-0">No goals logged yet.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="custom-card mb-md-0 mb-3">
            <div class="d-flex justify-content-start align-items-center mb-3">
                <h5 class="fw-bold m-0 me-2">Goals by Category</h5>
                <span> (all goals)</span>
            </div>
            @if (!$goalsByCategory->isEmpty())
                <!-- pie chart of goals by category (exercise, sleep, mood) -->
                <canvas style="max-height:275px;" id="goalInsightsPieChart"></canvas>
            @else
                <div class="text-center text-muted p-4">
                    <p class="mb-0">No goals logged yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@if (!$fullGoalStatusCounts->isEmpty())
    @php
        $goalStatusEmojiMap = [
            'completed' => '✅',
            'partially' => '🟡',
            'incomplete' => '❌',
            'unlogged' => '📭',
            'upcoming' => '⏳',
        ];

        $goalStatusColors = [
            'completed' => '#2ecc71',
            'partially' => '#f1c40f',
            'incomplete' => '#e74c3c',
            'unlogged' => '#95a5a6',
            'upcoming' => '#3498db',
        ];

        // Sort fullGoalStatusCounts descending
        $sortedCounts = $fullGoalStatusCounts->sortDesc();

        // Prepare final arrays
        $goalStatusLabelsFormatted = $sortedCounts->keys()->map(function ($label) use ($goalStatusEmojiMap) {
            return ($goalStatusEmojiMap[$label] ?? '') . ' ' . ucfirst($label);
        });

        $goalStatusColorsSorted = $sortedCounts->keys()->map(fn($label) => $goalStatusColors[$label] ?? '#7f8c8d');
    @endphp

    <script>
        const goalStatusLabels = @json($goalStatusLabelsFormatted);
        const goalStatusData = @json($sortedCounts->values());
        const goalStatusColors = @json($goalStatusColorsSorted);

        new Chart(document.getElementById('goallsDonutChart'), {
            type: 'doughnut',
            data: {
                labels: goalStatusLabels,
                datasets: [{
                    data: goalStatusData,
                    backgroundColor: goalStatusColors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label;
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} goal${value !== 1 ? 's' : ''} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
@if (!$goalsByCategory->isEmpty())
    @php
        $orderedInsightGoals = $goalsByCategory->sortByDesc('count');
        $goalInsightLabels = $orderedInsightGoals->pluck('GoalCategory');
        $goalInsightData = $orderedInsightGoals->pluck('count');
        $goalTotal = $goalInsightData->sum();
    @endphp

    <!-- Goals Pie Chart -->
    <script>
        const goalLabels = @json($goalInsightLabels);
        const goalInsightData = @json($goalInsightData);
        const goalDataTotal = goalInsightData.reduce((a, b) => a + b, 0);

        const colorInsightPalette = [
            '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6',
            '#1abc9c', '#e67e22', '#34495e', '#27ae60', '#8e44ad',
            '#d35400', '#c0392b', '#16a085', '#2980b9', '#7f8c8d',
            '#f1c40f', '#e91e63', '#00bcd4', '#4caf50', '#ff5722'
        ];

        const backgroundInsightColors = labels.map((_, index) => colorInsightPalette[index % colorInsightPalette.length]);

        new Chart(document.getElementById("goalInsightsPieChart"), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: goalInsightData,
                    backgroundColor: backgroundInsightColors,
                }]
            },
            options: {
                animation: {
                    animateScale: true, // Enables scaling animation
                    animateRotate: true // Optional: animates rotation from 0 to full
                },
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label;
                                var value = context.raw;
                                var logLabel = value === 1 ? 'log' : 'logs';
                                var percent = ((value / goalTotal) * 100).toFixed(0);
                                return `${label}: ${value} ${logLabel} (${percent}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        return {
                                            text: `${label}`,
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
@if (!$weeklyCompletionRates->isEmpty())
    <script>
        const weekGoalsLabels = @json($weekLabels);
        const weeklyRates = @json($weeklyCompletionRates);

        new Chart(document.getElementById("goalsLineChart"), {
            type: 'line',
            data: {
                labels: weekGoalsLabels,
                datasets: [{
                    label: 'Weekly Goal Completion (%)',
                    data: weeklyRates,
                    fill: true,
                    tension: 0.1,
                    borderColor: '##1e1e76',
                    backgroundColor: '#1e1e7633',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Completion Rate: ${context.raw}%`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Completion Rate (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week Starting'
                        }
                    }
                }
            }
        });
    </script>
@endif
@if (!$completedGoalsByCategory->isEmpty())
    <script>
        const categoryLabels = @json($completedGoalsByCategory->pluck('GoalCategory'));
        const categoryCounts = @json($completedGoalsByCategory->pluck('count'));

        new Chart(document.getElementById("goalsBarChart"), {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Completed Goals',
                    data: categoryCounts,
                    backgroundColor: ['#3498db', '#2ecc71', '#e67e22', '#9b59b6', '#f1c40f', '#e74c3c',
                        '#1abc9c', '#34495e'
                    ],
                    borderColor: '#34495e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Completed Goals'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Goal Category'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} completed`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
