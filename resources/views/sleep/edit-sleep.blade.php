@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Edit Sleep Log";
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
            <a href="{{ route('sleep.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Edit Sleep Log
            </a>
        </div>

        <!-- Log Sleep Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('sleep.update', $sleepLog->SleepLogID) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">Sleep Details</h1>
                            <p class="custom-header-desc mb-1">Update your sleep log details</p>

                            <!-- Sleep Date Selection -->
                            <div class="mb-3 mt-1 w-50 pe-4 mobile-full-width">
                                <label for="SleepDate" class="form-label fw-semibold mb-1">Bedtime Date</label>
                                <input type="date" max="{{ now()->format('Y-m-d') }}"
                                    class="me-4 form-control custom-input @error('SleepDate') is-invalid @enderror"
                                    id="SleepDate" name="SleepDate"
                                    value="{{ old('SleepDate', isset($sleepLog) ? $sleepLog->SleepDate->format('Y-m-d') : '') }}">
                                @error('SleepDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sleep Time Selection -->
                            <div class="d-flex bed-wake-stack">
                                <!-- Bedtime Selection -->
                                <div class="mb-3 w-50 me-4 mobile-full-width">
                                    <label for="BedTime" class="form-label fw-semibold mb-1">Bedtime</label>
                                    <input type="time"
                                        class="form-control custom-input @error('BedTime') is-invalid @enderror"
                                        id="BedTime" name="BedTime"
                                        value="{{ old('BedTime', isset($sleepLog) ? $sleepLog->BedTime->format('H:i') : '') }}">
                                    @error('BedTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Wake Time Selection -->
                                <div class="mb-3 w-50 me-4 mobile-full-width">
                                    <label for="WakeTime" class="form-label fw-semibold mb-1">Wake Time</label>
                                    <input type="time"
                                        class="form-control custom-input @error('WakeTime') is-invalid @enderror"
                                        id="WakeTime" name="WakeTime"
                                        value="{{ old('WakeTime', isset($sleepLog) ? $sleepLog->WakeTime->format('H:i') : '') }}">
                                    @error('WakeTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sleep Duration Calculation -->
                            <div class="mb-3 d-none" id="SleepDurationDisplayDiv">
                                <label for="SleepDuration" class="form-label fw-semibold mb-1">Sleep
                                    Duration</label><br>
                                <!-- Sleep duration display box, hidden initially -->
                                <div id="SleepDurationDisplay" class="p-2 rounded fs-5 fw-bold d-inline-block"
                                    style="background-color: var(--secondary-colour); color: white;">
                                    <!-- Content will be inserted by JS -->
                                </div>
                            </div>

                            <!-- Sleep Quality Selection -->
                            <div class="mb-1">
                                <label for="SleepQuality" class="form-label fw-semibold mb-1">Sleep Quality</label>
                            </div>

                            <!-- Sleep Quality Selection -->
                            <div
                                class="d-flex flex-wrap justify-content-start justify-content-xl-between mb-3 w-100 me-4">
                                @foreach ([5 => '😴 Excellent', 4 => '😊 Good', 3 => '😐 Fair', 2 => '😩 Poor', 1 => '😣 Very Poor'] as $value => $label)
                                    @php

                                        [$emoji, $text] = explode(' ', $label, 2); // Split emoji and text
                                        // Add red border class if validation error on QualityId
                                        $borderClass = $errors->has('QualityId') ? 'border border-danger' : '';
                                    @endphp
                                    <div
                                        class="quality-option text-center px-1 py-1 mb-2 me-2 mb-xl-0 me-xl-0 {{ $borderClass }}">
                                        <input type="radio" id="quality_{{ $value }}" name="QualityId"
                                            value="{{ $value }}" class="d-none"
                                            {{ old('QualityId', $sleepLog->SleepQuality) == $value ? 'checked' : '' }}>
                                        <label for="quality_{{ $value }}"
                                            class="d-flex flex-column align-items-center justify-content-center mb-0"
                                            style="cursor: pointer; height: 100%;">
                                            <span style="font-size: 2rem;">{{ $emoji }}</span>
                                            <span class="quality-text mt-1 fw-semibold">{{ $text }}</span>
                                        </label>
                                    </div>
                                @endforeach
                                <!-- Inline error message -->
                                @error('QualityId')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div style="color: var(--secondary-colour)" class="content-wrapper">
                            <label for="Notes" class="form-label fw-semibold mb-2 content-label">Notes</label>

                            <textarea class="content-area-height mb-4 form-control custom-input @error('Notes') is-invalid @enderror" id="Notes"
                                name="Notes" placeholder="Write your thoughts here...">{{ old('Notes', $sleepLog->Notes) }}</textarea>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn custom-form-btn">
                            Update Sleep Log
                        </button>
                    </div>
                </div>
        </div>
        </form>
    </div>
</div>
</div>
@include('main.footer')
