@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Admin - Add Resource";
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
            <a href="{{ route('admin.dashboard') }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Add Support Resource
            </a>
        </div>

        <!-- Add Resource Form -->
        <div class="custom-form-container p-4">
            <form method="POST" action="{{ route('admin.store.resource') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="left-column-wrapper d-flex flex-column h-100">
                            <h1 class="custom-header-title fw-bold mb-1">Resource Details</h1>
                            <p class="custom-header-desc mb-1">Create a new support resource for students.</p>

                            <div class="mb-3">
                                <label for="ResourceTitle" class="form-label fw-semibold mb-1">Title</label>
                                <input type="text"
                                    class="form-control custom-input @error('ResourceTitle') is-invalid @enderror"
                                    id="ResourceTitle" name="ResourceTitle" value="{{ old('ResourceTitle') }}"
                                    placeholder="Enter a resource title">
                                @error('ResourceTitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="category-container mb-3">
                                <label for="ResourceCategory" class="form-label fw-semibold mb-1">Category</label>
                                <select class="form-select custom-input @error('ResourceCategory') is-invalid @enderror"
                                    id="ResourceCategory" name="ResourceCategory">
                                    <option value="">Select a category</option>
                                    @foreach ($resource_categories as $category)
                                        <option value="{{ $category->ResourceCategoryID }}"
                                            {{ old('ResourceCategory') == $category->ResourceCategoryID ? 'selected' : '' }}>
                                            {{ $category->Name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ResourceCategory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Phone" class="form-label fw-semibold mb-1">Phone</label>
                                <input type="text"
                                    class="form-control custom-input @error('Phone') is-invalid @enderror"
                                    id="Phone" name="Phone" value="{{ old('Phone') }}"
                                    placeholder="Enter a phone number">
                                @error('Phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="Location" class="form-label fw-semibold mb-1">Location</label>
                                <input type="text"
                                    class="form-control custom-input @error('Location') is-invalid @enderror"
                                    id="Location" name="Location" value="{{ old('Location') }}"
                                    placeholder="Enter a location">
                                @error('Location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="content-wrapper">
                            <label for="Description"
                                class="form-label fw-semibold mb-2 content-label">Description</label>
                            <textarea class="form-control custom-input content-area-height @error('Description') is-invalid @enderror"
                                id="Description" name="Description" placeholder="Enter resource details...">{{ old('Description') }}</textarea>
                            @error('Description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn custom-form-btn">
                            Create Resource
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('main.footer')
