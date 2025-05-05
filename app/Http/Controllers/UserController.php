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
            'signupemail' => ['required', 'email', Rule::unique('users', 'email')],
            'signuppassword' => ['required', 'min:3', 'max:200'],
            'signuppassword_confirmation' => ['required', 'same:signuppassword']
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name cannot exceed 30 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'last_name.max' => 'Last name cannot exceed 30 characters.',
            'signupemail.required' => 'Email is required.',
            'signupemail.email' => 'Please enter a valid email address.',
            'signupemail.unique' => 'Email already exists. Please use a different email.',
            'signuppassword.required' => 'Password is required.',
            'signuppassword.min' => 'Password must be at least 3 characters.',
            'signuppassword.max' => 'Password cannot exceed 200 characters.',
            'signuppassword_confirmation.required' => 'Password confirmation is required.',
            'signuppassword_confirmation.same' => 'Passwords do not match.'
        ]);

        // Hash password
        $incomingFields['signuppassword'] = bcrypt($incomingFields['signuppassword']);

        // Rename fields to match User model and database
        $incomingFields['email'] = (string) $incomingFields['signupemail'];
        $incomingFields['password'] = (string) $incomingFields['signuppassword'];

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
