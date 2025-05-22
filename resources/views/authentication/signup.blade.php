@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Sign Up";
</script>
<div class="content-area py-4">
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
            </div>
        @else
            <div class="signup-card-wrapper">
                {{-- If not logged in, show the Sign Up form --}}
                <div class="login-card"> {{-- Reuse the login card style --}}
                    <h2 class="page-title mb-3">Your Details</h2>
                    {{-- Registration Form --}}
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        {{-- First Name --}}
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" value="{{ old('first_name') }}"
                                placeholder="Enter your first name" autofocus>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" value="{{ old('last_name') }}"
                                placeholder="Enter your last name">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="signupemail" class="form-label">Email</label>
                            <input type="email" class="form-control @error('signupemail') is-invalid @enderror"
                                id="signupemail" name="signupemail" value="{{ old('signupemail') }}"
                                placeholder="Enter your email">
                            @error('signupemail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="signuppassword" class="form-label">Password</label>
                            <input type="password" class="form-control @error('signuppassword') is-invalid @enderror"
                                id="signuppassword" name="signuppassword" placeholder="Create a password">
                            @error('signuppassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3">
                            <label for="signuppassword_confirmation" class="form-label">Confirm Password</label>
                            <input type="password"
                                class="form-control @error('signuppassword_confirmation') is-invalid @enderror"
                                id="signuppassword_confirmation" name="signuppassword_confirmation"
                                placeholder="Confirm your password">
                            @error('signuppassword_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-signin">Sign Up</button>
                        </div>

                        <div class="signup-prompt">
                            Already have an account? <a href="{{ route('login') }}">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

    </div> {{-- End signin-page-bg --}}
</div>
@include('main.footer')
