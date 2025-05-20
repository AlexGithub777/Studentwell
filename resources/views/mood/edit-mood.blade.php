@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Track Mood";
</script>
<div class="content-area py-4">
    <div class="container">
        <!-- Page Title and back link -->
        <div class="mb-3">
            <a href="{{ route('mood.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Track Your Mood
            </a>
        </div>

        <!-- Track Mood Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('mood.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">How are ypu feeling right now?</h1>
                            <p class="custom-header-desc mb-1">Track your emotional state to monitor your mental
                                wellbeing </p>

                            <div class="mb-2">
                                <label for="PostTitle" class="form-label fw-semibold mb-1">Select your current
                                    mood</label>
                                <!-- Mood Selection -->
                            </div>


                            <div class="mb-2">
                                <label for="PostTitle" class="form-label fw-semibold mb-1">Select your current
                                    emotions</label>
                                <!-- Emotions Selection (display as badge rounded-pill background var(--secondary_colour) text-colour white-->
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="content-wrapper">
                            <label for="Reflection" class="form-label fw-semibold mb-2 content-label">Reflection</label>
                            <p class="content-header-desc mb-1">Consider these prompts:</p>
                            <ul>
                                <li>What made you feel this way?</li>
                                <li>Were there and specific triggers for your mood?</li>
                                <li>How did your mood affect your productivity or focus?</li>
                                <li>Did you try any coping strategies? Were they effective?</li>
                                <li>What could you do differently tomorrow?</li>
                            </ul>

                            <textarea class="form-control custom-input content-area-height @error('Relfection') is-invalid @enderror"
                                id="Relfection" name="Relfection" placeholder="Write your thoughts here..." required>{{ old('Relfection') }}</textarea>
                            @error('Reflection')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Add Mood
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
