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
                @include('health-insights.overview')
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'mood' ? 'show active' : '' }}" id="mood"
                role="tabpanel" aria-labelledby="mood-tab">
                <!-- Mood content -->
                @include('health-insights.mood')
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'exercise' ? 'show active' : '' }}"
                id="exercise" role="tabpanel" aria-labelledby="exercise-tab">
                <!-- Exercise content -->
                @include('health-insights.exercise')
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'sleep' ? 'show active' : '' }}"
                id="sleep" role="tabpanel" aria-labelledby="sleep-tab">
                <!-- Sleep content -->
                @include('health-insights.sleep')
            </div>
            <div class="tab-pane fade {{ ($currentTab ?? 'overview') === 'goals' ? 'show active' : '' }}"
                id="goals" role="tabpanel" aria-labelledby="goals-tab">
                <!-- Goals content -->
                @include('health-insights.goals')
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
<!-- Js for overview content -->
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
        'rgba(255, 99, 132, 1)', // Red
        'rgba(54, 162, 235, 1)', // Blue
        'rgba(255, 206, 86, 1)', // Yellow
        'rgba(75, 192, 192, 1)' // Green
    ];

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

    new Chart(document.getElementById("overviewMoodLineChart"), {
        type: 'line',
        data: {
            labels: moodLabels,
            datasets: [{
                label: 'Mood Rating:',
                data: moodScores,
                borderColor: '#1e1e76',
                backgroundColor: '#1e1e76',
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
<!-- Mood insights -->
@php
    $reversedMood30days = $moodRatings30days->sortBy('MoodDate');
    $moodLabels30days = $reversedMood30days->pluck('MoodDate')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
    $moodScores30days = $reversedMood30days->pluck('MoodRating');
@endphp
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
                label: 'Mood Rating:',
                data: moodScores30days,
                borderColor: '#1e1e76',
                backgroundColor: '#1e1e76',
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
<!-- Mood Distribution Pie Chart -->
<script>
    const moodsMap = {
        1: '😢 Sad',
        2: '😔 Down',
        3: '😐 Okay',
        4: '😊 Good',
        5: '😄 Great'
    };

    const moodCounts = @json($moodDistribution);

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

    // Map colors to match the data order
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
                            const percentage = ((value / moodTotal) * 100).toFixed(0);
                            return `${context.label}: ${value} logs (${percentage}%)`;
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
                                    const percentage = ((value / moodTotal) * 100).toFixed(0);
                                    return {
                                        text: `${label}: ${value} logs (${percentage}%)`,
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
                            const days = label === 'Days Logged' ? loggedDays : unloggedDays;
                            return `${label}: ${days} days (${value.toFixed(1)}%)`;
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
                                    const days = label === 'Days Logged' ? loggedDays :
                                        unloggedDays;
                                    return {
                                        text: `${label}: ${days} days (${value.toFixed(1)}%)`,
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
                            const percentage = ((value / emotionTotal) * 100).toFixed(0);
                            return `${context.label}: ${value} logs (${percentage}%)`;
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
                                const percentage = ((value / emotionTotal) * 100).toFixed(0);
                                return {
                                    text: `${label}: ${value} logs (${percentage}%)`,
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
<!-- Exercise insights -->
@php
    $reversedExercise = $exerciseLogs->sortBy('ExerciseDateTime');
    $exerciseLabels = $reversedExercise
        ->pluck('ExerciseDateTime')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
    $exerciseDurations = $reversedExercise->pluck('DurationMinutes');
@endphp
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
            }]
        },
        options: {
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
                backgroundColor: backgroundColors,
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
                        label: function(context) {
                            const index = context.dataIndex;
                            const status = statuses[index];
                            const count = data[index];
                            const percentage = percentages[index];
                            return `${status}: ${count} logs (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
<script>
    const exerciseTypesData = @json($exerciseTypesDistribution);

    const rawLabels = Object.keys(exerciseTypesData);
    const rawExerciseData = Object.values(exerciseTypesData);
    const totalLogs = rawExerciseData.reduce((sum, val) => sum + val, 0);

    const exerciseTypeBackgroundColours = [
        'rgba(255, 99, 132, 1)', // Red
        'rgba(54, 162, 235, 1)', // Blue
        'rgba(255, 206, 86, 1)', // Yellow
        'rgba(75, 192, 192, 1)', // Green
        'rgba(153, 102, 255, 1)', // Purple
        'rgba(255, 159, 64, 1)' // Orange
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
    const sortedRawLabels = exerciseData.map(item => item.label);

    new Chart(document.getElementById('exercisePieChart'), {
        type: 'pie',
        data: {
            labels: sortedLabels,
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
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
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


@include('main.footer')
