@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Sleep Logging";
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
                    <h1 class="page-title mb-1">Sleep Logging</h1>
                </div>
                <a href="{{ route('sleep.log') }}" class="btn add-btn text-white"
                    style="background-color: var(--secondary-colour);">
                    <i class="fas fa-plus me-1 fw-bold"></i> Log Sleep
                </a>
            </div>
        </div>

        <!-- Sleep metrics -->
        <div class="d-flex flex-wrap justify-content-center row gx-4 mb-4">
            <!-- Average Sleep Duration -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Average Sleep Duration</h5>
                    <div class="d-flex">
                        <i class="fas fa-bed metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $averageSleepDuration ?? 'No data' }}
                            </h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average sleep quality -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Average Sleep Quality</h5>
                    <div class="d-flex">
                        <div class="metric-emoji">
                            {{ $averageSleepQualityEmoji ?? '😐' }}
                        </div>
                        <div class="metric-text ms-3 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $averageSleepQualityLabel ?? 'No data' }}
                            </h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Days tracked this week -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Nights Logged</h5>
                    <div class="d-flex">
                        <i class="fas fa-moon metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $nightsLoggedThisWeek }} / 7 Nights</h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sleep tracking streak -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Sleep Log Streak</h5>
                    <div class="d-flex">
                        <i class="fas fa-fire metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $sleepLogStreak }} {{ $sleepLogStreak === 1 ? 'day' : 'days' }}
                            </h5>
                            <p class="tracked-time mb-0">Consecutive logging</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col d-flex justify-content-between align-items-center mt-4">
            <div>
                <h3 class="page-subtitle">Sleep Records</h3>
            </div>

            <!-- Sleep Quality Dropdown -->
            @php
                $selectedFilter = request()->query('filter');
                $selectedEntry = collect($uniqueQualities)->firstWhere('Label', $selectedFilter);
            @endphp

            <div class="dropdown mb-3">
                <button style="background-color: #1e1e76; color: white;" class="btn dropdown-toggle ms-2" type="button"
                    id="sleepFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if ($selectedFilter && $selectedEntry)
                        {{ $selectedEntry->Emoji }} {{ $selectedFilter }}
                    @else
                        Filter by Sleep Quality
                    @endif
                </button>
                <ul class="dropdown-menu" aria-labelledby="sleepFilterDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('sleep.index') }}">
                            All
                        </a>
                    </li>
                    @foreach ($uniqueQualities as $quality)
                        <li>
                            <a class="dropdown-item" href="{{ route('sleep.index', ['filter' => $quality->Label]) }}">
                                {{ $quality->Emoji }} {{ $quality->Label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Sleep Log History -->
        @if ($sleepLogs->isEmpty())
            <div class="alert alert-info">
                No sleep log history found.
            </div>
        @else
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @foreach ($sleepLogs as $sleepLog)
                    <div class="col">
                        <div class="custom-card d-flex flex-column justify-content-between h-100"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add emoji based on sleep quality -->
                                        <div class="metric-emoji">
                                            {{ $sleepLog->SleepQualityEmoji }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                <!-- Convert sleep duration to hours and minutes from SleepDurationMinutes -->
                                                {{ floor($sleepLog->SleepDurationMinutes / 60) }}h
                                                {{ $sleepLog->SleepDurationMinutes % 60 }}m
                                            </h5>
                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                {{ \Carbon\Carbon::parse($sleepLog->SleepDate)->format('F jS, Y') }}
                                                @if (\Carbon\Carbon::parse($sleepLog->SleepDate)->isYesterday())
                                                    (Last Night)
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Display sleep duration in 12-hour format -->
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $sleepLog->BedTime->format('g:i A') }} -
                                                {{ $sleepLog->WakeTime->format('g:i A') }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $sleepLog->Notes }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- wrap the content in a div to allow for overflow -->
                            <div class="card-content">
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="badge rounded-pill mb-md-2 px-3 py-2"
                                            style="background-color: var(--secondary-colour); color: white; width: fit-content;">
                                            {{ $sleepLog->SleepQualityLabel }}
                                        </span>

                                    </div>
                                    <div>
                                        <!-- Edit Button -->
                                        @auth
                                            @if (auth()->id() === $sleepLog->UserID)
                                                <!-- Add update button -->
                                                <a href="{{ route('sleep.edit', $sleepLog->SleepLogID) }}"
                                                    class="btn btn-sm"
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
                {{ $sleepLogs->links('pagination::bootstrap-5') }}
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
