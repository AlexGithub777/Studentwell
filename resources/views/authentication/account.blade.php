@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Account";
</script>
{{-- Display Success Message --}}
@if (session('success'))
    <div class="alert alert-success mb-3" id="account-alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="content-area py-4">
    <div class="container mb-3">
        <h1 class="page-title mt-4">Account</h1>
    </div>
    {{-- Wrapper div for centering --}}
    <div class="signup-page-bg">
        <div class="signup-card-wrapper">
            <div class="login-card"> {{-- Reuse the login card style --}}
                <h2 class="page-title mb-3">Your Details</h2>
                {{-- Account Form --}}
                <form id="account-form" action="{{ route('account.edit') }}" method="POST">
                    @csrf
                    {{-- First Name --}}
                    <div class="mb-3">
                        <label for="accountfirst_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('accountfirst_name') is-invalid @enderror"
                            id="accountfirst_name" name="accountfirst_name"
                            value="{{ old('accountfirst_name', $user->first_name ?? '') }}" required autofocus>
                        @error('accountfirst_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div class="mb-3">
                        <label for="accountlast_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('accountlast_name') is-invalid @enderror"
                            id="accountlast_name" name="accountlast_name"
                            value="{{ old('accountlast_name', $user->last_name ?? '') }}" required>
                        @error('accountlast_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="accountemail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('accountemail') is-invalid @enderror"
                            id="accountemail" name="accountemail" value="{{ old('accountemail', $user->email ?? '') }}"
                            required>
                        @error('accountemail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="newpassword" class="form-label">Password</label>
                        <input type="password" class="form-control @error('newpassword') is-invalid @enderror"
                            id="newpassword" name="newpassword" placeholder="Enter new password">
                        @error('newpassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="newpassword_confirmation" class="form-label">Confirm Password</label>
                        <input type="password"
                            class="form-control @error('newpassword_confirmation') is-invalid @enderror"
                            id="newpassword_confirmation" name="newpassword_confirmation"
                            placeholder="Confirm your new password">
                        @error('newpassword_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </form>

                <!-- Buttons for Edit Account and Delete Account -->
                <div class="d-flex justify-content-between mt-4">
                    {{-- Delete Account Form (separate form) --}}
                    <form action="{{ route('account.delete') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete your account?');" class="me-2 w-50">
                        @csrf
                        <button type="submit" style="color: white;" class="btn btn-danger fw-bold w-100">Delete
                            Account</button>
                    </form>

                    {{-- Edit Account Button (trigger form submission with JavaScript) --}}
                    <button onclick="document.getElementById('account-form').submit();" class="btn btn-signin w-50">Edit
                        Account</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const alert = document.getElementById('account-alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // fully remove after fade out
        }
    }, 10000); // 10000 ms = 10 seconds
</script>
@include('main.footer')
