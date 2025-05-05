@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Forum - Create Post";
</script>
<div class="content-area py-4">
    <div class="container">
        <!-- Page Title and back link -->
        <div class="mb-3">
            <a href="{{ route('forum.index') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i> Create New Post
            </a>
        </div>

        <!-- Post Form -->
        <div class="post-form-container p-4">
            <form method="POST" action="{{ route('forum.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="post-header-title fw-bold mb-1">Post Content</h1>
                            <p class="post-header-desc mb-1">Share your thoughts, questions, or experiences with the
                                community</p>

                            <div class="mb-2">
                                <label for="PostTitle" class="form-label fw-semibold mb-1">Title</label>
                                <input type="text"
                                    class="form-control custom-input @error('PostTitle') is-invalid @enderror"
                                    id="PostTitle" name="PostTitle" value="{{ old('PostTitle') }}"
                                    placeholder="Enter a descriptive title" required>
                                @error('PostTitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="category-container">
                                <label for="PostCategory" class="form-label fw-semibold mb-1">Category</label>
                                <select class="form-select custom-input @error('PostCategory') is-invalid @enderror"
                                    id="PostCategory" name="PostCategory" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <option value="General">General</option>
                                    <option value="Mental Health">Mental Health</option>
                                    <option value="Physical Health">Physical Health</option>
                                    <option value="Exercise">Exercise</option>
                                    <option value="Nutrition">Nutrition</option>
                                    <option value="Sleep">Sleep</option>
                                    <option value="Study">Study</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('PostCategory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="content-wrapper">
                            <label for="Content" class="form-label fw-semibold mb-2 content-label">Content</label>
                            <textarea class="form-control custom-input content-area-height @error('Content') is-invalid @enderror" id="Content"
                                name="Content" placeholder="Share your thoughts, questions, or experiences..." required>{{ old('Content') }}</textarea>
                            @error('Content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn publish-btn">
                            Publish Post
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
