@include('main.header')
@include('main.sidebar')
<div class="content-area">
    <div class="container mb-3">
        <h1 class="page-title mt-4">Sign Up</h1>
    </div>
    {{-- Wrapper div for centering --}}
    <div class="signup-page-bg">

        {{-- Check if user is already authenticated --}}
        @auth
            {{-- If logged in, show message and logout --}}
            <div class="text-center" style="color: var(--secondary-colour);">
                <p>You are already logged in.</p>
                <form action="{{ route('logout') }}" method="POST" class="d-inline"> {{-- Use logout route --}}
                    @csrf
                    <button type="submit" class="btn btn-logout">Log out</button>
                </form>
                {{-- <a href="/dashboard" class="btn btn-link" style="color: var(--secondary-colour);">Go to Dashboard</a> --}}
            </div>
        @else
            <div class="signup-card-wrapper">
                {{-- If not logged in, show the Sign Up form --}}
                <div class="login-card"> {{-- Reuse the login card style --}}
                    <h2 class="page-title mb-3">Your Details</h2>
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3"
                            style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li> {{-- List all errors for registration --}}
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- Registration Form --}}
                    {{-- Make sure action points to your registration route --}}
                    <form action="{{ route('register') }}" method="POST"> {{-- Use named route 'register' if defined --}}
                        @csrf

                        {{-- First Name Input --}}
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="Enter your first name" value="{{ old('first_name') }}" required autofocus>
                        </div>

                        {{-- Last Name Input --}}
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Enter your last name" value="{{ old('last_name') }}" required>
                        </div>

                        {{-- Email Input --}}
                        <div class="mb-3">
                            <label for="signupemail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="signupemail" name="signupemail"
                                placeholder="Enter your email" value="{{ old('signupemail') }}" required>
                        </div>

                        {{-- Password Input --}}
                        <div class="mb-3">
                            <label for="signuppassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="signuppassword" name="signuppassword"
                                placeholder="Create a password" required>
                            {{-- Optional: Add password requirements hint --}}
                            {{-- <small class="form-text" style="color: var(--secondary-colour); opacity: 0.8;">Minimum 8 characters</small> --}}
                        </div>

                        {{-- Confirm Password Input --}}
                        <div class="mb-3">
                            <label for="signuppassword_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="signuppassword_confirmation"
                                name="signuppassword_confirmation" placeholder="Confirm your password" required>
                        </div>


                        {{-- Sign Up Button --}}
                        <div class="d-grid mt-4">
                            {{-- Reuse btn-signin style but change text --}}
                            <button type="submit" class="btn btn-signin">Sign Up</button>
                        </div>


                        <div class="signup-prompt"> {{-- Reuse class, content changes --}}
                            Already have an account? <a href="{{ route('login') }}">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

    </div> {{-- End signin-page-bg --}}
</div>
@include('main.footer')
