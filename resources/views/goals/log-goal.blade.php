@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Log Goal";
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
        <!-- Page Title and back link -->
        <div class="mb-3">
            <a href="{{ route('goals.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Log Goal
            </a>
        </div>

        <!-- Log Goal Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('goals.store.log', $goal->GoalID) }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">Record Goal Outcome</h1>
                            <p class="custom-header-desc mb-1">Track whether you accomplished this goal</p>
                            @php
                                $today = now()->toDateString();
                            @endphp

                            <!-- Goal Log Date -->
                            <div class="mb-3 mt-1 w-50 pe-4 mobile-full-width">
                                <label for="GoalLogDate" class="form-label fw-semibold mb-1">Date</label>
                                <input type="date" class="me-4 form-control custom-input" id="GoalLogDate"
                                    name="GoalLogDate" value="{{ $today }}" readonly>
                            </div>

                            <!-- Goal completion status radio buttons -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Did you complete this goal?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input log-goal-radio @error('GoalStatus') is-invalid @enderror"
                                            type="radio" name="GoalStatus" id="GoalStatusYes" value="completed"
                                            {{ old('GoalStatus') == 'completed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="GoalStatusYes">Yes</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input log-goal-radio @error('GoalStatus') is-invalid @enderror"
                                            type="radio" name="GoalStatus" id="GoalStatusNo" value="incomplete"
                                            {{ old('GoalStatus') == 'incomplete' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="GoalStatusNo">No</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input log-goal-radio @error('GoalStatus') is-invalid @enderror"
                                            type="radio" name="GoalStatus" id="GoalStatusPartial" value="partially"
                                            {{ old('GoalStatus') == 'partially' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="GoalStatusPartial">Partially</label>
                                    </div>
                                </div>

                                @error('GoalStatus')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="content-wrapper">
                            <label for="Notes" class="form-label fw-semibold mb-2 content-label">Notes</label>
                            <textarea class="form-control custom-input content-area-height @error('Notes') is-invalid @enderror" id="Notes"
                                name="Notes" placeholder="Notes about how the goal was completed or why it wasn't...">{{ old('Notes') }}</textarea>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Log Goal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
