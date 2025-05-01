@include('main.header')
@include('main.navbar')
<!--MAIN AREA -->
<h1>SIGN IN PAGE</h1>
<div class="signin-style">
    <h2>Sign In</h2>
    <form action="/signin" method="POST"> @csrf
        <input name="signinemail" type="text" placeholder="email"><br>
        <input name="signinpassword" type="password" placeholder="password"><br>
        <button>Sign In</button>
    </form>
</div>
@include('main.footer')
