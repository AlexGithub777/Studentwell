@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Edit Goal";
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
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Edit Goal
            </a>
        </div>

        <!-- Update Goal Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('goals.update', $goal->GoalID) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">Goal Details</h1>
                            <p class="custom-header-desc mb-1">Define your wellness goal</p>

                            <!-- Goal Title -->
                            <div class="mb-3">
                                <label for="GoalTitle" class="form-label fw-semibold mb-1">Title</label>
                                <input type="text"
                                    class="form-control custom-input @error('GoalTitle') is-invalid @enderror"
                                    id="GoalTitle" name="GoalTitle" value="{{ old('GoalTitle', $goal->GoalTitle) }}"
                                    placeholder="Enter a clear, specific goal">
                                @error('GoalTitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Goal Category -->
                            <div class="category-container mb-3">
                                <label for="GoalCategory" class="form-label fw-semibold mb-1">Category</label>
                                @php
                                    $goalCategories = [
                                        'Academic' => '📘',
                                        'Career' => '💼',
                                        'Finance' => '🐖',
                                        'Hobbies' => '🎨',
                                        'Mental Health' => '🧠',
                                        'Nutrition' => '🍎',
                                        'Physical Health' => '🏋️',
                                        'Productivity' => '📋',
                                        'Sleep' => '🛏️',
                                        'Social' => '👥',
                                        'Spiritual' => '🙏',
                                        'Travel' => '✈️',
                                        'Wellness' => '❤️',
                                        'Other' => '❓',
                                    ];
                                @endphp

                                <select class="form-select custom-input @error('GoalCategory') is-invalid @enderror"
                                    id="GoalCategory" name="GoalCategory">
                                    <option value="">Select a category</option>
                                    @foreach ($goalCategories as $category => $emoji)
                                        <option value="{{ $category }}"
                                            {{ old('GoalCategory', $goal->GoalCategory) == $category ? 'selected' : '' }}>
                                            {{ $emoji }} {{ $category }}
                                        </option>
                                    @endforeach
                                </select>


                                @error('GoalCategory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @php
                                $today = now()->toDateString();
                            @endphp

                            <!-- Goal Start Date -->
                            <div class="mb-3 mt-1 w-50 pe-4 mobile-full-width">
                                <label for="GoalStartDate" class="form-label fw-semibold mb-1">Goal Start Date</label>
                                <input type="date"
                                    class="me-4 form-control custom-input @error('GoalStartDate') is-invalid @enderror"
                                    id="GoalStartDate" name="GoalStartDate"
                                    value="{{ old('GoalStartDate', isset($goal) ? $goal->GoalStartDate->format('Y-m-d') : '') }}"
                                    min="{{ $today }}">
                                @error('GoalStartDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Goal Target Date -->
                            <div class="mb-3 mt-1 w-50 pe-4 mobile-full-width">
                                <label for="GoalTargetDate" class="form-label fw-semibold mb-1">Goal Target Date</label>
                                <input type="date"
                                    class="me-4 form-control custom-input @error('GoalTargetDate') is-invalid @enderror"
                                    id="GoalTargetDate" name="GoalTargetDate"
                                    value="{{ old('GoalTargetDate', isset($goal) ? $goal->GoalTargetDate->format('Y-m-d') : '') }}"
                                    min="{{ old('GoalStartDate', $goal->GoalStartDate->format('Y-m-d') ?? $today) }}">
                                @error('GoalTargetDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Goal Notes -->
                        <div class="content-wrapper">
                            <label for="Notes" class="form-label fw-semibold mb-2 content-label">Notes</label>
                            <textarea class="form-control custom-input content-area-height @error('Notes') is-invalid @enderror" id="Notes"
                                name="Notes" placeholder="Any other details about your goal...">{{ old('Notes', $goal->Notes) }}</textarea>
                            @error('Notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- submit button -->
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Edit Goal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
