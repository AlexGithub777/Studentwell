@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Mood Tracking";
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
        <!-- Page Title, Subtitle, and New Post Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Mood Tracking</h1>
                </div>
                <a href="{{ route('mood.track') }}" class="btn add-btn text-white"
                    style="background-color: var(--secondary-colour);">
                    <i class="fas fa-plus me-1 fw-bold"></i> Track Mood
                </a>
            </div>
        </div>

        <!-- Mood metrics -->
        <div class="d-flex flex-wrap justify-content-center row gx-4 mb-4">
            <!-- Todays mood -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Today's Mood</h5>
                    <div class="d-flex">
                        <div class="metric-emoji">
                            {{ $todayMood?->MoodEmoji ?? '😐' }}
                        </div>
                        <div class="metric-text ms-3 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $todayMood?->MoodLabel ?? 'No entry today' }}
                            </h5>
                            <p class="tracked-time mb-0">
                                {{ $todayMood ? 'Tracked ' . \Carbon\Carbon::parse($todayMood->created_at)->diffForHumans() : '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average mood -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Average Mood</h5>
                    <div class="d-flex">
                        <div class="metric-emoji">
                            {{ $averageMoodEmoji ?? '😐' }}
                        </div>
                        <div class="metric-text ms-3 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $averageMoodLabel ?? 'No data' }}
                            </h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Days tracked this week -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Days Tracked</h5>
                    <div class="d-flex">
                        <i class="fas fa-calendar-week metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $daysTrackedThisWeek }} / 7 days</h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mood tracking streak -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Mood Streak</h5>
                    <div class="d-flex">
                        <i class="fas fa-fire metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $moodLogStreak }} {{ $moodLogStreak === 1 ? 'day' : 'days' }}
                            </h5>
                            <p class="tracked-time mb-0">Consecutive tracking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col d-flex justify-content-between align-items-center mt-4">
            <div>
                <h3 class="page-subtitle">Mood History</h3>
            </div>

            <!-- Mood Filter Dropdown -->
            @php
                $selectedMood = request()->query('filter');
                $selectedMoodEntry = collect($moodMap)->firstWhere('label', $selectedMood);
            @endphp

            <div class="dropdown">
                <button style="background-color: #1e1e76; color: white;" class="btn dropdown-toggle mb-2" type="button"
                    id="moodFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if ($selectedMood && $selectedMoodEntry)
                        {{ $selectedMoodEntry['emoji'] }} {{ $selectedMood }}
                    @else
                        Filter by Mood
                    @endif
                </button>
                <ul class="dropdown-menu" aria-labelledby="moodFilterDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('mood.index') }}">
                            All
                        </a>
                    </li>
                    @foreach ($uniqueMoods as $mood)
                        <li>
                            <a class="dropdown-item" href="{{ route('mood.index', ['filter' => $mood->MoodLabel]) }}">
                                {{ $mood->MoodEmoji }} {{ $mood->MoodLabel }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Mood History -->
        @if ($moodLogs->isEmpty())
            <div class="alert alert-info">
                No mood history found.
            </div>
        @else
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @foreach ($moodLogs as $moodLog)
                    <div class="col">
                        <div class="custom-card d-flex flex-column justify-content-between h-100"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add emoji based on mood -->
                                        <div class="metric-emoji">
                                            {{ $moodLog->MoodEmoji }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                {{ $moodLog->MoodLabel }}
                                            </h5>
                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                {{ \Carbon\Carbon::parse($moodLog->MoodDate)->format('F jS, Y') }}
                                                @if (\Carbon\Carbon::parse($moodLog->MoodDate)->isToday())
                                                    (Today)
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $moodLog->Reflection }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- wrap the content in a div to allow for overflow -->
                            <div class="card-content">
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <!-- Display emtotions in seperate badege pills -->
                                        @php
                                            $emotions = json_decode($moodLog->Emotions ?? '[]', true);
                                        @endphp

                                        @if (is_array($emotions) && count($emotions) > 0)
                                            @foreach ($emotions as $emotion)
                                                <span class="badge rounded-pill mb-2 mb-lg-0 px-3 py-2"
                                                    style="background-color: var(--secondary-colour); color: white; width: fit-content;">
                                                    {{ $emotion }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        <!-- Edit Button -->
                                        @auth
                                            @if (auth()->id() === $moodLog->UserID)
                                                <!-- Add update button -->
                                                <a href="{{ route('mood.edit', $moodLog->MoodLogID) }}" class="btn btn-sm"
                                                    style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $moodLogs->links('pagination::bootstrap-5') }}
            </div>
    </div>
</div>
@endif
</div>
</div>
<script>
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // fully remove after fade out
        }
    }, 10000); // 10000 ms = 10 seconds
</script>
@include('main.footer')
