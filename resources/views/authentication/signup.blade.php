@include('main.header')
@include('main.navbar')
<!--MAIN AREA -->
<h1>SIGN UP PAGE</h1>
@auth
    <p>Congrats, you are logged in.</p>
    <!--Add logout button-->
    <form action="/logout" method="POST">
        @csrf <button>Log out</button> </form>
@else
    <div class="signup-style">
        <h2>Sign Up</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/signup" method="POST"> @csrf
            <input name="first_name" type="text" placeholder="First Name" value="{{ old('first_name') }}"><br>
            <input name="last_name" type="text" placeholder="Last Name" value="{{ old('last_name') }}"><br>
            <input name="email" type="text" placeholder="email" value="{{ old('email') }}"><br>
            <input name="password" type="password" placeholder="password"><br>
            <!-- Add a confirm password field -->
            <button>Sign Up</button>
        </form>
    </div>

@endauth
@include('main.footer')
