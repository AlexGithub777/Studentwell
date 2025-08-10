@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Sign In";
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
                    <img src="{{ asset('assets/images/studentwell-logo.png') }}" alt="Logo" class="logo img-fluid"
                        style="max-height: 35px;">
                </div>

                {{-- Login Form --}}
                <form action="{{ route('login') }}" method="POST" autocomplete="on">
                    @csrf

                    {{-- Email Input --}}
                    <div class="mb-3">
                        <label for="signinemail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('signinemail') is-invalid @enderror"
                            id="signinemail" name="signinemail" placeholder="Enter your email"
                            value="{{ old('signinemail') }}" autocomplete="email" autofocus>
                        @error('signinemail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="mb-2">
                        <label for="signinpassword" class="form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control @error('signinpassword') is-invalid @enderror"
                                id="signinpassword" name="signinpassword" placeholder="Enter your password"
                                autocomplete="current-password" style="padding-right: 45px;">
                            <button class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                type="button" id="togglePassword"
                                style="padding: 0; margin-right: 12px; color: var(--secondary-colour);">
                                <i class="fas fa-eye-slash" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('signinpassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sign In Button --}}
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-signin">Sign In</button>
                    </div>

                    <div class="signup-prompt">
                        Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
                    </div>
                </form>
            </div>
        @endauth

    </div>
</div>
@include('main.footer')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('signinpassword');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    });
</script>
