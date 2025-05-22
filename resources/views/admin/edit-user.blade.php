@include('main.header')
@include('main.sidebar')

<script>
    document.title = "StudentWell | Edit User";
</script>

@if (session('success'))
    <div class="alert alert-success mb-3" id="edit-user-alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="content-area py-4">
    <div class="container mb-3">
        <!-- Back Link and Header -->
        <div class="mb-3">
            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="back-link h2 fw-bold">
                <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Edit User
            </a>
        </div>

    </div>

    <div class="signup-page-bg">
        <div class="signup-card-wrapper">
            <div class="login-card">
                <h2 class="page-title mb-3">User Details</h2>

                <form id="edit-user-form" action="{{ route('admin.update.user', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $user->first_name) }}">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $user->last_name) }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control custom-input @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Dropdown -->
                    <div class="category-container mb-3">
                        <label for="role" class="form-label fw-semibold mb-1">Role</label>
                        <select style="height: 50px"
                            class="form-select custom-input @error('role') is-invalid @enderror" id="role"
                            name="role">
                            <option value="">Select a role</option>
                            <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="Student" {{ old('role', $user->role) == 'Student' ? 'selected' : '' }}>
                                Student</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Optional Password Change -->
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control custom-input @error('password') is-invalid @enderror"
                            placeholder="Leave blank to keep current password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control custom-input @error('password_confirmation') is-invalid @enderror"
                            placeholder="Re-enter new password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button INSIDE FORM -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-signin w-100">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.getElementById('edit-user-alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 10000);
</script>

@include('main.footer')
