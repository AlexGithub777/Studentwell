@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Account";
</script>
<div class="content-area">
    <div class="container mb-3">
        <h1 class="page-title mt-4">Account</h1>
    </div>
    {{-- Wrapper div for centering --}}
    <div class="signup-page-bg">
        <div class="signup-card-wrapper">
            <div class="login-card"> {{-- Reuse the login card style --}}
                <h2 class="page-title mb-3">Your Details</h2>

                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-3"
                        style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Display Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success mb-3" id="account-alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Account Form --}}
                <form id="account-form" action="{{ route('account.edit') }}" method="POST">
                    @csrf

                    {{-- First Name --}}
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="accountfirst_name" name="accountfirst_name"
                            value="{{ $user->first_name ?? '' }}" required autofocus>
                    </div>

                    {{-- Last Name --}}
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="accountlast_name" name="accountlast_name"
                            value="{{ $user->last_name ?? '' }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="temail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="accountemail" name="accountemail"
                            value="{{ $user->email ?? '' }}" required>
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="newpassword" name="newpassword"
                            placeholder="Enter new password">
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="newpassword_confirmation"
                            name="newpassword_confirmation" placeholder="Confirm your new password">
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
