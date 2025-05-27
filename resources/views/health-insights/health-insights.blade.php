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
                            <!-- line graph of mood over time (30 days)-->
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
    new Chart(document.getElementById("logsBarChart"), {
        type: 'bar',
        data: {
            labels: ['Mood', 'Exercise', 'Sleep', 'Goals'],
            datasets: [{
                label: 'Logs Count',
                data: [12, 19, 7, 5], // replace with dynamic data
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
<script>
    new Chart(document.getElementById("moodLineChart"), {
        type: 'line',
        data: {
            labels: Array.from({
                length: 30
            }, (_, i) => `Day ${i + 1}`), // 30 days
            datasets: [{
                label: 'Mood Score',
                data: [3, 2, 5, 4, 6, 7, 5, 4, 3, 2, 4, 5, 6, 7, 8, 6, 5, 4, 3, 2, 1, 2, 3, 4, 5, 6, 7,
                    8, 9, 10
                ], // example data
                borderColor: 'rgba(75,192,192,1)',
                fill: false
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
<script>
    new Chart(document.getElementById("sleepDonutChart"), {
        type: 'doughnut',
        data: {
            labels: ['<6 hrs', '6–8 hrs', '8+ hrs'],
            datasets: [{
                data: [5, 15, 10], // example
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
<script>
    new Chart(document.getElementById("goalsPieChart"), {
        type: 'pie',
        data: {
            labels: ['Exercise', 'Sleep', 'Mood'],
            datasets: [{
                data: [10, 5, 3],
                backgroundColor: ['#ffcd56', '#4bc0c0', '#9966ff']
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
@include('main.footer')
