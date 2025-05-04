<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        //Get data sent from HTML form
        $incomingFields = $request->validate([
            'first_name' => ['required', 'min:2', 'max:30'],
            'last_name' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:3', 'max:200']
        ]);
        //Write user into table "users"
        $incomingFields['password'] = bcrypt($incomingFields['password']); //encrypt or hash password
        $user = User::create($incomingFields); //utility function: Model::create() is to add data to a table.
        // Model::find() is used to search data; Model::getQuery()
        // Model::all() return all records in the table.

        //Login feature
        auth()->login($user); //call utility function login() of auth() object
        return redirect('/signup'); //redirect back to signup page.
    }
    public function signin(Request $request)
    {
        $incomingFields = $request->validate([
            'signinemail' => 'required',
            'signinpassword' => 'required'
        ]);
        if (auth()->attempt([
            'email' => $incomingFields['signinemail'],
            'password' => $incomingFields['signinpassword']
        ])) {
            $request->session()->regenerate();
        }

        //Check if login user is admin or normal user
        if (auth()->user()->role == 'Admin') {
            return redirect('/dashboard');
        } else {
            return redirect('/signup'); //redirect back to signup page.
        }
        return redirect('/signup'); //redirect back to signup page.
    }
    public function logout()
    {
        auth()->logout();
        return redirect('/'); //Redirect back to home page
    }
}
