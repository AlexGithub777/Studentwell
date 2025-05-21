@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Edit Mood";
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
            <a href="{{ route('mood.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Edit Your Mood
            </a>
        </div>
        <!-- Track Mood Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('mood.update', $moodLog->MoodLogID) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">How are you feeling right now?</h1>
                            <p class="custom-header-desc mb-1">Update your emotional state details to monitor your
                                mental
                                wellbeing </p>

                            <div class="mb-2">
                                <label for="Mood Rating" class="form-label fw-semibold mb-1">Select your current
                                    mood</label>
                            </div>

                            <!-- Mood Selection -->
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                @foreach ([5 => '😄 Great', 4 => '😊 Good', 3 => '😐 Okay', 2 => '😔 Down', 1 => '😢 Sad'] as $value => $label)
                                    @php
                                        [$emoji, $text] = explode(' ', $label);
                                        // Add red border class if validation error on MoodId
                                        $borderClass = $errors->has('MoodId') ? 'border border-danger' : '';
                                    @endphp
                                    <div class="mood-option text-center px-1 py-1 {{ $borderClass }}">
                                        <input type="radio" id="mood_{{ $value }}" name="MoodId"
                                            value="{{ $value }}" class="d-none"
                                            {{ old('MoodId', $moodLog->MoodRating) == $value ? 'checked' : '' }}>
                                        <label for="mood_{{ $value }}"
                                            class="d-flex flex-column align-items-center justify-content-center mb-0"
                                            style="cursor: pointer; height: 100%;">
                                            <span style="font-size: 2rem;">{{ $emoji }}</span>
                                            <span class="mood-text mt-1 fw-semibold">{{ $text }}</span>
                                        </label>
                                    </div>
                                @endforeach
                                <!-- Inline error message -->
                                @error('MoodId')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="Emotions" class="form-label fw-semibold mb-2">Select your current
                                    emotions</label>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @php
                                        $emotions = [
                                            'Adventurous',
                                            'Anxious',
                                            'Bored',
                                            'Calm',
                                            'Content',
                                            'Creative',
                                            'Excited',
                                            'Focused',
                                            'Frustrated',
                                            'Grateful',
                                            'Hopeful',
                                            'Irritable',
                                            'Lonely',
                                            'Motivated',
                                            'Overwhelmed',
                                            'Productive',
                                            'Relaxed',
                                            'Social',
                                            'Stressed',
                                            'Tense',
                                            'Tired',
                                        ];

                                    @endphp

                                    @php
                                        $selectedEmotions = old(
                                            'emotions',
                                            json_decode($moodLog->Emotions ?? '[]', true),
                                        );
                                    @endphp

                                    @foreach ($emotions as $emotion)
                                        <div class="emotion-option">
                                            <input type="checkbox" name="Emotions[]" value="{{ $emotion }}"
                                                id="emotion_{{ $emotion }}" class="form-check-input"
                                                {{ in_array($emotion, $selectedEmotions) ? 'checked' : '' }}>
                                            <label class="badge" for="emotion_{{ $emotion }}">
                                                {{ ucfirst($emotion) }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            @error('Emotions')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div style="color: var(--secondary-colour)" class="content-wrapper">
                            <label for="Reflection" class="form-label fw-semibold mb-2 content-label">Reflection</label>
                            <p class="content-header-desc mb-1">Consider these prompts:</p>
                            <ul>
                                <li style="color: var(secondary-colour)">What made you feel this way?</li>
                                <li>Were there any specific triggers for your mood?</li>
                                <li>How did your mood affect your productivity or focus?</li>
                                <li>Did you try any coping strategies? Were they effective?</li>
                                <li>What could you do differently tomorrow?</li>
                            </ul>

                            <textarea style="min-height: 200px;" class="form-control custom-input @error('Reflection') is-invalid @enderror"
                                id="Reflection" name="Reflection" placeholder="Write your thoughts here...">{{ old('Reflection', $moodLog->Reflection) }}</textarea>
                            @error('Reflection')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Update Mood
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@include('main.footer')
