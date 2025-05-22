@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Sign In";
</script>
<div class="content-area py-4">
    <div class="container mb-3">
        <h1 class="page-title mt-4">Sign In</h1>
    </div>
    <div class="signin-page-bg">
        {{-- Check if user is already authenticated --}}
        @auth
            {{-- If logged in, show a message and a logout button --}}
            <div class="text-center" style="color: var(--secondary-colour);">
                <p>You are already logged in.</p>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">Log out</button>
                </form>
            </div>
        @else
            {{-- If not logged in, show the Sign In form --}}
            <div class="login-card">

                {{-- Brand/Logo --}}
                <div class="login-brand">
                    <img src="{{ asset('assets/images/studentwell-logo.png') }}" alt="Logo" class="logo"
                        height="35px">
                </div>

                {{-- Login Form --}}
                <form action="{{ route('login') }}" method="POST"> {{-- Use named route 'login' if defined --}}
                    @csrf

                    {{-- Email Input --}}
                    <div class="mb-3">
                        <label for="signinemail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('signinemail') is-invalid @enderror"
                            id="signinemail" name="signinemail" placeholder="Enter your email"
                            value="{{ old('signinemail') }}" autofocus>
                        @error('signinemail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="mb-2"> {{-- Reduced margin bottom before forgot password link --}}
                        <label for="signinpassword" class="form-label">Password</label>
                        <input type="password" class="form-control @error('signinpassword') is-invalid @enderror"
                            id="signinpassword" name="signinpassword" placeholder="Enter your password">
                        @error('signinpassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sign In Button --}}
                    <div class="d-grid mt-4"> {{-- Use d-grid for full-width button --}}
                        <button type="submit" class="btn btn-signin">Sign In</button>
                    </div>

                    <div class="signup-prompt">
                        Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
                        {{-- Use named route 'register' if defined --}}
                    </div>
                </form>
            </div>
        @endauth

    </div>
</div>
@include('main.footer')
