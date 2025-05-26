@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Exercise Planning & Tracking";
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
        <!-- Page Title, Subtitle, and Set Goal Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Exercise Planning & Tracking</h1>
                </div>

                <div>
                    <!-- Plan Exercise Button -->
                    <a href="{{ route('exercise.plan') }}" class="btn add-btn me-2 text-white"
                        style="background-color: var(--secondary-colour);">
                        <i class="fas fa-plus me-1 fw-bold"></i> Plan Exericse
                    </a>

                    <!-- Log Exercise Button -->
                    <a href="{{ route('exercise.log') }}" class="btn add-btn text-white"
                        style="background-color: var(--secondary-colour);">
                        <i class="fas fa-plus me-1 fw-bold"></i> Log Exercise
                    </a>
                </div>
            </div>
        </div>

        <!-- Exercise metrics -->
        <div class="d-flex flex-wrap justify-content-center row gx-4 mb-4">

            <!-- Exercises completed this week -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Exercises Completed</h5>
                    <div class="d-flex">
                        <i class="fas fa-check metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $totalCompletedExercisesThisWeek ?? '0' }}
                            </h5>
                            <p class="tracked-time mb-0">
                                This Week
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exercises Missed -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Exercises Missed</h5>
                    <div class="d-flex">
                        <i class="fas fa-xmark metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $totalMissedExercisesThisWeek ?? '0' }}</h5>
                            <p class="tracked-time mb-0">This Week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time spent exercising this week -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Time Spent Exercising</h5>
                    <div class="d-flex">
                        <i class="fas fa-clock metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $totalTimeExercisedThisWeek ?? '0' }} mins</h5>
                            <p class="tracked-time mb-0">Total this week</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exercise Streak -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Exercise Streak</h5>
                    <div class="d-flex">
                        <i class="fas fa-fire metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $exerciseLogStreak }} {{ $exerciseLogStreak === 1 ? 'day' : 'days' }}
                            </h5>
                            <p class="tracked-time mb-0">Consecutive logging</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row row-cols-1 row-cols-md-2 g-4">
            <!-- Exericse History -->
            <div class="col-xxl-6">
                <h3 class="page-subtitle">Exercise History</h3>
                @if ($loggedExercises->isEmpty())
                    <div class="alert alert-info">
                        No exercise history found.
                    </div>
                @else
                    @foreach ($loggedExercises as $exerciseLog)
                        <div style="min-height: 190px;"
                            class="custom-card d-flex flex-column justify-content-between mb-3"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add icon based on Exercise Type (from controller) -->
                                        <i class="fas {{ $exerciseLog->ExerciseTypeIcon }} metric-icon me-2 p-0">
                                        </i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                {{ $exerciseLog->ExerciseType ?? '' }}
                                            </h5>
                                            @php
                                                $exerciseDate = \Carbon\Carbon::parse($exerciseLog->ExerciseDateTime);
                                            @endphp

                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                @if ($exerciseDate->isYesterday())
                                                    Yesterday {{ $exerciseDate->format('g:ia') }}
                                                @else
                                                    {{ $exerciseDate->format('F jS, Y g:ia') }}
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Display exercise log notes -->
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $exerciseLog->DurationMinutes ?? '' }} minutes -
                                                {{ $exerciseLog->Notes ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Status section with consistent centering -->
                                    <div style="min-width: 82px"
                                        class="d-flex flex-column align-items-center text-center">
                                        @if ($exerciseLog->Status === 'Completed')
                                            <i class="fas fa-check metric-icon p-0 mb-1"></i>
                                            <span style="font-size: 0.875rem;">Completed</span>
                                        @elseif ($exerciseLog->Status === 'Missed')
                                            <i class="fas fa-xmark metric-icon p-0 mb-1"></i>
                                            <span style="font-size: 0.875rem;">Missed</span>
                                        @elseif ($exerciseLog->Status === 'Unplanned')
                                            <i class="fas fa-question metric-icon p-0 mb-1"></i>
                                            <span style="font-size: 0.875rem;">Unplanned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- wrap the content in a div to allow for overflow -->
                            <div class="card-content">
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="badge rounded-pill mb-md-2 px-3 py-2"
                                            style="background-color: var(--secondary-colour); color: white; width: fit-content;">
                                            {{ $exerciseLog->ExerciseIntensity }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $loggedExercises->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
            <!-- Upcoming Exercises -->
            <div class="col-xxl-6">
                <h3 class="page-subtitle">Upcoming Exercises</h3>
                @if ($plannedExercises->isEmpty())
                    <div class="alert alert-info">
                        No upcoming exercises found.
                    </div>
                @else
                    @foreach ($plannedExercises as $exercisePlan)
                        <div style="min-height: 190px;"
                            class="custom-card d-flex flex-column justify-content-between mb-3"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add icon based on Exercise Type -->
                                        <i class="fas {{ $exercisePlan->ExerciseTypeIcon }} metric-icon me-2">
                                            <!-- Add ExerciseTypeIcon to model in controller -->
                                        </i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                {{ $exercisePlan->ExerciseType }}
                                            </h5>

                                            @php
                                                $exerciseDate = \Carbon\Carbon::parse($exercisePlan->ExerciseDateTime);
                                            @endphp

                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                @if ($exerciseDate->isTomorrow())
                                                    Tomorrow {{ $exerciseDate->format('g:ia') }}
                                                @else
                                                    {{ $exerciseDate->format('F jS, Y g:ia') }}
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Display exercise plan notes -->
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $exercisePlan->Notes ?? '' }}
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
                                            {{ $exercisePlan->ExerciseIntensity }}
                                        </span>
                                    </div>
                                    <div>
                                        <!-- Edit and log Button -->
                                        @auth
                                            @if (auth()->id() === $exercisePlan->UserID)
                                                <!--edit button -->
                                                <a href="{{ route('exercise.edit', $exercisePlan->PlannedExerciseID) }}"
                                                    class="btn btn-sm me-1"
                                                    style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>

                                                <!-- log button -->
                                                <a href="{{ route('exercise.log', $exercisePlan->PlannedExerciseID) }}"
                                                    class="btn btn-sm"
                                                    style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                                    Log Exercise
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $plannedExercises->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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
