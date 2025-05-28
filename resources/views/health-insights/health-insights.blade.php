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
                <a class="mb-2 mb-sm-0 nav-link {{ ($currentTab ?? 'overview') === 'overview' ? 'active' : '' }}"
                    id="overview-tab" data-bs-toggle="pill" href="#overview" role="tab" aria-controls="overview"
                    aria-selected="{{ ($currentTab ?? 'overview') === 'overview' ? 'true' : 'false' }}"
                    data-tab="overview">
                    Overview
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link  {{ ($currentTab ?? 'overview') === 'mood' ? 'active' : '' }}" id="mood-tab"
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
@include('main.footer')
