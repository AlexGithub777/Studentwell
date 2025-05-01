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
        <form action="/signup" method="POST"> @csrf
            <input name="name" type="text" placeholder="name"><br> <input name="email" type="text"
                placeholder="email"><br> <input name="password" type="password" placeholder="password"><br>
            <button>Sign Up</button>
        </form>
    </div>

@endauth
@include('main.footer')
