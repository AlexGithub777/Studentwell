@include('main.header')
@include('main.sidebar')
<!-- Chart JS Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.title = "StudentWell | Health Insights";
</script>
@if (session('success'))
    <div class="alert alert-success" id="alert-success">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="alert alert-danger" id="alert-success">
        {{ session('error') }}
    </div>
@endif
<div class="content-area py-4">
    <div class="container">
        <!-- Page Title, Subtitle, and Add Resource Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Health Insights</h1>
                </div>
            </div>
        </div>

        <!-- Tabs (Overview, Mood, Exercise, Sleep, Goals) -->
        <ul class="nav nav-pills mb-4" id="insightsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ ($currentTab ?? 'overview') === 'overview' ? 'active' : '' }}" id="overview-tab"
                    data-bs-toggle="pill" href="#overview" role="tab" aria-controls="overview"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'overview' ? 'true' : 'false' }}"
                    data-tab="overview">
                    Overview
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ ($currentTab ?? 'overview') === 'mood' ? 'active' : '' }}" id="mood-tab"
                    data-bs-toggle="pill" href="#mood" role="tab" aria-controls="mood"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'mood' ? 'true' : 'false' }}" data-tab="mood">
                    Mood
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ ($currentTab ?? 'overview') === 'exercise' ? 'active' : '' }}" id="exercise-tab"
                    data-bs-toggle="pill" href="#exercise" role="tab" aria-controls="exercise"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'exercise' ? 'true' : 'false' }}"
                    data-tab="exercise">
                    Exercise
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ ($currentTab ?? 'overview') === 'sleep' ? 'active' : '' }}" id="sleep-tab"
                    data-bs-toggle="pill" href="#sleep" role="tab" aria-controls="sleep"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'sleep' ? 'true' : 'false' }}" data-tab="sleep">
                    Sleep
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ ($currentTab ?? 'overview') === 'goals' ? 'active' : '' }}" id="goals-tab"
                    data-bs-toggle="pill" href="#goals" role="tab" aria-controls="goals"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'goals' ? 'true' : 'false' }}" data-tab="goals">
                    Goals
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="insightsTabContent">
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'overview' ? 'show active' : '' }}"
                id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <!-- Overview content -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-card mb-md-0 mb-3">
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <h5 class="fw-bold m-0 me-2">Your Weekly Wellness Activity</h5>
                                <span> (log counts across features)</span>
                            </div>
                            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                            <canvas style="max-height:275px;" id="logsBarChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card mb-md-0 mb-3">
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <h5 class="fw-bold m-0 me-2">How Consistent Your Sleep Has Been</h5>
                                <span> (daily hours of sleep)</span>
                            </div>
                            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
                            <canvas style="max-height:275px;" id="sleepDonutChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-0 mt-md-4">
                    <div class="col-md-6">
                        <div class="custom-card mb-md-0 mb-3">
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <h5 class="fw-bold m-0 me-2">How Your Mood Has Shifted</h5>
                                <span> (14-day trend)</span>
                            </div>
                            <!-- line graph of mood over time (14 days)-->
                            <canvas style="max-height:275px;" id="moodLineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card mb-md-0 mb-3">
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <h5 class="fw-bold m-0 me-2">Where Your Goals Are Focused</h5>
                                <span> (goals by category)</span>
                            </div>
                            <!-- pie chart of goals by category (exercise, sleep, mood) -->
                            <canvas style="max-height:275px;" id="goalsPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'mood' ? 'show active' : '' }}" id="mood"
                role="tabpanel" aria-labelledby="mood-tab">
                <!-- Mood content -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 1
                            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 2
                            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
                        </div>
                    </div>
                </div>
                <div class="row mt-0 mt-md-4">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 3
                            <!-- line graph of mood over time (30 days)-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            <!-- pie chart of goals by category (exercise, sleep, mood) -->
                            Card 4
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'exercise' ? 'show active' : '' }}"
                id="exercise" role="tabpanel" aria-labelledby="exercise-tab">
                <!-- Exercise content -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 1
                            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 2
                            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
                        </div>
                    </div>
                </div>
                <div class="row mt-0 mt-md-4">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 3
                            <!-- line graph of mood over time (30 days)-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            <!-- pie chart of goals by category (exercise, sleep, mood) -->
                            Card 4
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'sleep' ? 'show active' : '' }}"
                id="sleep" role="tabpanel" aria-labelledby="sleep-tab">
                <!-- Sleep content -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 1
                            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 2
                            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
                        </div>
                    </div>
                </div>
                <div class="row mt-0 mt-md-4">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 3
                            <!-- line graph of mood over time (30 days)-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            <!-- pie chart of goals by category (exercise, sleep, mood) -->
                            Card 4
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'goals' ? 'show active' : '' }}"
                id="goals" role="tabpanel" aria-labelledby="goals-tab">
                <!-- Goals content -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 1
                            <!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 2
                            <!-- donut graph of sleep consistency (daily hours of sleep) need to change -->
                        </div>
                    </div>
                </div>
                <div class="row mt-0 mt-md-4">
                    <div class="col-md-6">
                        <div class="custom-card">
                            Card 3
                            <!-- line graph of mood over time (30 days)-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-card">
                            <!-- pie chart of goals by category (exercise, sleep, mood) -->
                            Card 4
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabLinks = document.querySelectorAll("#insightsTabs .nav-link");

        tabLinks.forEach((link) => {
            link.addEventListener("click", function(e) {
                e.preventDefault();

                const tabName = this.getAttribute("data-tab");

                // Define the route mapping
                const routes = {
                    overview: '{{ route('health-insights.index') }}',
                    mood: '{{ route('health-insights.mood') }}',
                    exercise: '{{ route('health-insights.exercise') }}',
                    sleep: '{{ route('health-insights.sleep') }}',
                    goals: '{{ route('health-insights.goals') }}',
                };

                // Update URL without page reload
                history.pushState(null, null, routes[tabName]);

                // Trigger Bootstrap tab functionality
                const tabTrigger = new bootstrap.Tab(this);
                tabTrigger.show();
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener("popstate", function() {
            location.reload(); // Reload page to get correct tab state
        });
    });
</script>
<script>
    const rawData = [{
            label: 'Mood',
            value: {{ $moodLogCount }}
        },
        {
            label: 'Exercise',
            value: {{ $exerciseLogCount }}
        },
        {
            label: 'Sleep',
            value: {{ $sleepLogCount }}
        },
        {
            label: 'Goals',
            value: {{ $goalsLogCount }}
        }
    ];

    // Sort descending by value
    rawData.sort((a, b) => b.value - a.value);
    const barLabels = rawData.map(item => item.label);
    const barData = rawData.map(item => item.value);

    // Use different colors for each bar
    const barBackgroundColors = [
        'rgba(255, 99, 132, 0.6)', // Red
        'rgba(54, 162, 235, 0.6)', // Blue
        'rgba(255, 206, 86, 0.6)', // Yellow
        'rgba(75, 192, 192, 0.6)' // Green
    ];

    new Chart(document.getElementById("logsBarChart"), {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Logs Count',
                data: barData,
                backgroundColor: barBackgroundColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // This removes the legend/key
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
                    }
                }
            }
        }
    });
</script>
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

    new Chart(document.getElementById("moodLineChart"), {
        type: 'line',
        data: {
            labels: moodLabels,
            datasets: [{
                label: 'Mood Score',
                data: moodScores,
                borderColor: '#3498db',
                backgroundColor: '#3498db33',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mood Date'
                    }
                },
                y: {
                    min: 1,
                    max: 5,
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

<!-- Sleep Donut Chart -->
<script>
    const sleepData = @json(array_values($sleepConsistency));
    const sleepTotal = sleepData.reduce((a, b) => a + b, 0);

    new Chart(document.getElementById("sleepDonutChart"), {
        type: 'doughnut',
        data: {
            labels: ['<6 hrs', '6–8 hrs', '8+ hrs'],
            datasets: [{
                data: sleepData,
                backgroundColor: ['#e74c3c', '#f1c40f', '#2ecc71']
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
                            return `${context.label}: ${value} (${percentage}%)`;
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
                                        text: `${label}: ${percentage}%`,
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
                        const percentage = ((value / sleepTotal) * 100).toFixed(0);
                        return percentage + '%';
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
@php
    $orderedGoals = $goalsByCategory->sortByDesc('count');
    $goalLabels = $orderedGoals->pluck('GoalCategory');
    $goalData = $orderedGoals->pluck('count');
    $goalTotal = $goalData->sum();
@endphp

<!-- Goals Pie Chart -->
<script>
    const labels = @json($goalLabels);
    const data = @json($goalData);
    const goalTotal = data.reduce((a, b) => a + b, 0);

    // Define a diverse color palette with visually distinct colors
    const colorPalette = [
        '#e74c3c', // Bright red
        '#3498db', // Bright blue  
        '#2ecc71', // Bright green
        '#f39c12', // Orange
        '#9b59b6', // Purple
        '#1abc9c', // Teal/turquoise
        '#e67e22', // Dark orange
        '#34495e', // Dark blue-gray
        '#27ae60', // Forest green
        '#8e44ad', // Dark purple
        '#d35400', // Red-orange
        '#c0392b', // Dark red
        '#16a085', // Dark teal
        '#2980b9', // Medium blue
        '#7f8c8d', // Gray
        '#f1c40f', // Yellow
        '#e91e63', // Pink
        '#00bcd4', // Cyan
        '#4caf50', // Light green
        '#ff5722' // Deep orange
    ];

    // Assign unique colors by label index
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
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percent = ((value / goalTotal) * 100).toFixed(0);
                            return `${context.label}: ${value} (${percent}%)`;
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
                                    const percentage = ((value / goalTotal) * 100).toFixed(0);
                                    return {
                                        text: `${label}: ${percentage}%`,
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
                        return percentage >= 5 ? percentage + '%' :
                        ''; // Only show percentage if slice is >= 5%
                    }
                }
            }
        }
    });
</script>
@include('main.footer')
