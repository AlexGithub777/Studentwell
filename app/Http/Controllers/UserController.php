<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        // Validate input
        $incomingFields = $request->validate([
            'first_name' => ['required', 'min:2', 'max:30'],
            'last_name' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:3', 'max:200']
        ]);

        // Hash password
        $incomingFields['password'] = bcrypt($incomingFields['password']);

        // Create user
        $user = User::create($incomingFields);

        // Auto-login
        auth()->login($user);

        // Redirect to /home with success message
        return redirect('/home')->with('success', 'Account created successfully! Welcome aboard.');
    }

    public function signin(Request $request)
    {
        $incomingFields = $request->validate([
            'signinemail' => 'required|email',
            'signinpassword' => 'required'
        ], [
            'signinemail.required' => 'Email is required.',
            'signinemail.email' => 'Please enter a valid email address.',
            'signinpassword.required' => 'Password is required.'
        ]);        

        if (auth()->attempt([
            'email' => $incomingFields['signinemail'],
            'password' => $incomingFields['signinpassword']
        ])) {
            $request->session()->regenerate();

            // Check user role safely
            $user = auth()->user();
            if ($user && $user->role == 'Admin') {
                return redirect('/dashboard');
            } else {
                // Redirect to /home with success message
                return redirect('/home')->with('success', 'Logged in successfully!');
            }
        }

        // If authentication failed, redirect back with error
        return back()->withErrors([
            'signinemail' => 'Invalid email or password.'
        ])->withInput();
    }


    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
