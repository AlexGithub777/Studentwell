@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Log Exercise";
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
            <a href="{{ route('exercise.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Log Exercise
            </a>
        </div>

        <!-- Log Exercise Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('exercise.store.log', $exercisePlan->PlannedExerciseID ?? '') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">Exercise Details</h1>
                            <p class="custom-header-desc mb-3">Record your physical activity</p>

                            @php
                                $now = now()->format('Y-m-d\TH:i');
                                $exerciseTypes = [
                                    'Basketball' => '🏀',
                                    'Boxing' => '🥊',
                                    'Climbing' => '🧗',
                                    'Cycling' => '🚴',
                                    'Dance' => '💃',
                                    'Football' => '⚽',
                                    'Hiking' => '🥾',
                                    'Running' => '🏃',
                                    'Skating' => '⛸️',
                                    'Skiing' => '🎿',
                                    'Sports' => '🥇',
                                    'Swimming' => '🏊',
                                    'Tennis' => '🎾',
                                    'Volleyball' => '🏐',
                                    'Walking' => '🚶',
                                    'Weight Lifting' => '🏋️',
                                    'Yoga' => '🧘',
                                    'Other' => '❓',
                                ];
                            @endphp

                            <div>
                                <!-- Exercise completion status radio buttons - only show if exercisePlan exists -->
                                @if (isset($exercisePlan))
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold mb-1">Did you complete this planned
                                            exercise?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input log-goal-radio @error('Status') is-invalid @enderror"
                                                    type="radio" name="Status" id="StatusYes" value="Completed"
                                                    {{ old('Status', $exercisePlan->Status ?? '') == 'Completed' ? 'checked' : '' }}>
                                                <label class="form-check-label log-goal-label"
                                                    for="StatusYes">Yes</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input log-goal-radio @error('Status') is-invalid @enderror"
                                                    type="radio" name="Status" id="StatusNo" value="Missed"
                                                    {{ old('Status', $exercisePlan->Status ?? '') == 'Missed' ? 'checked' : '' }}>
                                                <label class="form-check-label log-goal-label" for="StatusNo">No</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input log-goal-radio @error('Status') is-invalid @enderror"
                                                    type="radio" name="Status" id="StatusPartial" value="Partially"
                                                    {{ old('Status', $exercisePlan->Status ?? '') == 'Partially' ? 'checked' : '' }}>
                                                <label class="form-check-label log-goal-label"
                                                    for="StatusPartial">Partially</label>
                                            </div>
                                        </div>

                                        @error('Status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Exercise Date and Time -->
                                <div class="mb-3">
                                    <label for="ExerciseDateTime" class="form-label fw-semibold mb-1">Date and
                                        Time</label>
                                    <input type="datetime-local" max="{{ $now }}"
                                        class="form-control custom-input @error('ExerciseDateTime') is-invalid @enderror"
                                        id="ExerciseDateTime" name="ExerciseDateTime"
                                        value="{{ old('ExerciseDateTime', $exercisePlan->ExerciseDateTime ?? $now) }}">
                                    @error('ExerciseDateTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Exercise Type -->
                                <div class="category-container mb-3">
                                    <label for="ExerciseType" class="form-label fw-semibold mb-1">Exercise
                                        Type</label>
                                    <select class="form-select custom-input @error('ExerciseType') is-invalid @enderror"
                                        id="ExerciseType" name="ExerciseType">
                                        <option value="">Select exercise type</option>
                                        @foreach ($exerciseTypes as $type => $emoji)
                                            <option value="{{ $type }}"
                                                {{ old('ExerciseType', $exercisePlan->ExerciseType ?? '') == $type ? 'selected' : '' }}>
                                                {{ $emoji }} {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ExerciseType')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Exercise Intensity -->
                                <div class="mb-3">
                                    <label for="ExerciseIntensity" class="form-label fw-semibold mb-1">Exercise
                                        Intensity</label>
                                    <select
                                        class="form-select custom-input @error('ExerciseIntensity') is-invalid @enderror"
                                        id="ExerciseIntensity" name="ExerciseIntensity">
                                        <option value="">Select intensity</option>
                                        <option value="Low Intensity"
                                            {{ old('ExerciseIntensity', $exercisePlan->ExerciseIntensity ?? '') == 'Low Intensity' ? 'selected' : '' }}>
                                            Low
                                        </option>
                                        <option value="Moderate Intensity"
                                            {{ old('ExerciseIntensity', $exercisePlan->ExerciseIntensity ?? '') == 'Moderate Intensity' ? 'selected' : '' }}>
                                            Moderate
                                        </option>
                                        <option value="High Intensity"
                                            {{ old('ExerciseIntensity', $exercisePlan->ExerciseIntensity ?? '') == 'High Intensity' ? 'selected' : '' }}>
                                            High
                                        </option>
                                    </select>
                                    @error('ExerciseIntensity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Exercise Duration -->
                                <div class="mb-3">
                                    <label for="DurationMinutes" class="form-label fw-semibold mb-1">Duration (in
                                        minutes)</label>
                                    <input type="number" min="1" max="1440"
                                        class="form-control custom-input @error('DurationMinutes') is-invalid @enderror"
                                        id="DurationMinutes" name="DurationMinutes"
                                        value="{{ old('DurationMinutes', $exercisePlan->DurationMinutes ?? '') }}">
                                    @error('DurationMinutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="content-wrapper">
                            <!-- Notes -->
                            <label for="Notes" class="form-label fw-semibold mb-2 content-label">Notes</label>
                            <textarea class="form-control custom-input content-area-height @error('Notes') is-invalid @enderror" id="Notes"
                                name="Notes" placeholder="Write your exercise notes here...">{{ old('Notes') }}</textarea>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Log Exercise
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
