@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Goal Setting";
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
                    <h1 class="page-title mb-1">Goal Setting</h1>
                </div>
                <a href="{{ route('goals.set') }}" class="btn add-btn text-white"
                    style="background-color: var(--secondary-colour);">
                    <i class="fas fa-plus me-1 fw-bold"></i> Set Goal
                </a>
            </div>
        </div>

        <!-- Goal metrics -->
        <div class="d-flex flex-wrap justify-content-center row gx-4 mb-4">

            <!-- Active Goals -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Active Goals</h5>
                    <div class="d-flex">
                        <i class="fas fa-bullseye metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $activeGoalCount ?? '0' }}
                            </h5>
                            <p class="tracked-time mb-0">Across {{ $activeGoalUniqueCategoryCount ?? '0' }} categories
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Goal Completion Rate (%) -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Completion Rate</h5>
                    <div class="d-flex">
                        <i class="fas fa-circle-check metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $GoalCompletionRate ?? '0' }}%</h5>
                            <p class="tracked-time mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed goals this month -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Completed Goals</h5>
                    <div class="d-flex">
                        <i class="fas fa-award metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">{{ $completedGoalCount ?? '0' }}</h5>
                            <p class="tracked-time mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Incomplete Goals this month -->
            <div class="col-lg-3 col-md-6 col-sm-10 mb-4 d-flex justify-content-center">
                <div class="login-card p-3 w-100">
                    <h5 class="fw-bold card-title">Incomplete Goals</h5>
                    <div class="d-flex">
                        <i class="fas fa-circle-xmark metric-icon"></i>
                        <div class="metric-text ms-4 mt-3">
                            <h5 class="fw-bold metric-value mb-1">
                                {{ $incompleteGoalCount ?? '0' }}
                            </h5>
                            <p class="tracked-time mb-0">This month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row row-cols-1 row-cols-md-2 g-4">
            <!-- Goal History -->
            <div class="col-xxl-6">
                <h3 class="page-subtitle">Goal History</h3>
                @if ($goalLogs->isEmpty())
                    <div class="alert alert-info">
                        No goal history found.
                    </div>
                @else
                    @foreach ($goalLogs as $goalLog)
                        <div style="min-height: 180px;"
                            class="custom-card d-flex flex-column justify-content-between mb-3"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add icon based on GoalStatus (fa-trophy for completed, fa-circle-xmark for incomplete, and fa-hourglass-half for partially) -->
                                        <i
                                            class="fas 
                                        @if ($goalLog->GoalStatus === 'completed') fa-trophy 
                                        @elseif ($goalLog->GoalStatus === 'incomplete') fa-circle-xmark 
                                        @elseif ($goalLog->GoalStatus === 'partially') fa-adjust @endif me-2 p-0 metric-icon">
                                        </i>
                                    </div>
                                    <div>
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                {{ $goalLog->goal->GoalTitle }} <!-- Check if correct implemented -->
                                            </h5>
                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                {{ $goalLog->GoalDays }} days - @if ($goalLog->GoalStatus === 'completed')
                                                    Completed on {{ $goalLog->GoalLogDate->format('F jS, Y') }}
                                                @elseif ($goalLog->GoalStatus === 'incomplete')
                                                    Failed on {{ $goalLog->GoalLogDate->format('F jS, Y') }}
                                                @elseif ($goalLog->GoalStatus === 'partially')
                                                    Partially completed on
                                                    {{ $goalLog->GoalLogDate->format('F jS, Y') }}
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Display goal notes -->
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $goalLog->goal->Notes ?? '' }}
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
                                            {{ $goalLog->goal->GoalCategory }}
                                        </span>
                                    </div>
                                    <div>
                                        <!-- goal log notes -->
                                        @if (!empty($goalLog->Notes))
                                            <span>
                                                <p class="ms-2"
                                                    style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                    <b>Note:</b> {{ $goalLog->Notes }}
                                                </p>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $goalLogs->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
            <!-- Active Goals -->
            <div class="col-xxl-6">
                <h3 class="page-subtitle">Active Goals</h3>
                @if ($goals->isEmpty())
                    <div class="alert alert-info">
                        No active goals found.
                    </div>
                @else
                    @foreach ($goals as $goal)
                        <div style="min-height: 180px;"
                            class="custom-card d-flex flex-column justify-content-between mb-3"
                            style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <!-- Add icon based on Goal Category -->
                                        <i class="fas {{ $goal->GoalCategoryIcon }} metric-icon p-0 me-2">
                                            <!-- Add GoalCategoryIcon to model in controller -->
                                        </i>
                                    </div>
                                    <div>
                                        <div class="d-flex">
                                            <h5 class="mb-0 me-2 fw-bold" style="color: var(--secondary-colour);">
                                                {{ $goal->GoalTitle }}
                                            </h5>
                                            <!-- Add goal start and target date -->
                                            <p style="margin:0; font-size:0.9rem; color: var(--secondary-colour);">
                                                {{ \Carbon\Carbon::parse($goal->GoalStartDate)->format('F jS, Y') }} to
                                                {{ \Carbon\Carbon::parse($goal->GoalTargetDate)->format('F jS, Y') }}
                                            </p>
                                        </div>
                                        <!-- Display active goal notes -->
                                        <div class="mt-1">
                                            <span style="font-size:1rem; color: var(--secondary-colour);">
                                                {{ $goal->Notes ?? '' }}
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
                                            {{ $goal->GoalCategory }}
                                        </span>

                                    </div>
                                    <div>
                                        <!-- Edit and log Button -->
                                        @auth
                                            @if (auth()->id() === $goal->UserID)
                                                <!--edit button -->
                                                <a href="{{ route('goals.edit', $goal->GoalID) }}" class="btn btn-sm me-1"
                                                    style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>

                                                <!-- log button -->
                                                <a href="{{ route('goals.log', $goal->GoalID) }}" class="btn btn-sm"
                                                    style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                                    Log Goal
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $goals->links('pagination::bootstrap-5') }}
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
