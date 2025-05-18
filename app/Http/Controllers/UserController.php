<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        // Validate input
        $incomingFields = $request->validate([
            'first_name' => ['required', 'min:2', 'max:30'],
            'last_name' => ['required', 'min:2', 'max:30'],
            'signupemail' => ['required', 'email', Rule::unique('users', 'email'), 'min:5', 'max:255'],
            'signuppassword' => ['required', 'min:6', 'max:255'],
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
            'signupemail.min' => 'Email must be at least 5 characters.',
            'signupemail.max' => 'Email cannot exceed 255 characters.',
            'signuppassword.required' => 'Password is required.',
            'signuppassword.min' => 'Password must be at least 3 characters.',
            'signuppassword.max' => 'Password cannot exceed 255 characters.',
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
                return redirect('/admin');
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

    public function showAccount(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/')->with('error', 'You must be logged in to view this page.');
        }

        // Return the account view with user data
        return view('authentication.account', [
            'user' => auth()->user()
        ]);
    }

    public function editAccount(Request $request)
    {
        // get the authenticated user
        $user = Auth::user();

        $validated = $request->validate([
            'accountfirst_name' => ['required', 'min:2', 'max:30'],
            'accountlast_name' => ['required', 'min:2', 'max:30'],
            'accountemail' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(Auth::id())
            ],
            'newpassword' => ['nullable', 'min:3', 'max:200'],
            'newpassword_confirmation' => ['nullable', 'same:newpassword']
        ], [
            'accountfirst_name.required' => 'First name is required.',
            'accountfirst_name.min' => 'First name must be at least 2 characters.',
            'accountfirst_name.max' => 'First name cannot exceed 30 characters.',
            'accountlast_name.required' => 'Last name is required.',
            'accountlast_name.min' => 'Last name must be at least 2 characters.',
            'accountlast_name.max' => 'Last name cannot exceed 30 characters.',
            'accountemail.required' => 'Email is required.',
            'accountemail.email' => 'Please enter a valid email address.',
            'accountemail.unique' => 'Email already exists. Please use a different email.',
            'newpassword.required' => 'Password is required.',
            'newpassword.min' => 'Password must be at least 3 characters.',
            'newpassword.max' => 'Password cannot exceed 200 characters.',
            'newpassword_confirmation.required' => 'Password confirmation is required.',
            'newpassword_confirmation.same' => 'Passwords do not match.'
        ]);

        // Update user info
        $user->first_name = $validated['accountfirst_name'];
        $user->last_name = $validated['accountlast_name'];
        $user->email = $validated['accountemail'];

        // Update password if provided
        if (!empty($validated['newpassword'])) {
            $user->password = Hash::make($validated['newpassword']);
        }

        $user->save();

        return redirect()->route('account.show')->with('success', 'Account updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        Auth::logout(); // Log out user

        $user->delete(); // Delete account

        return redirect('/')->with('success', 'Your account has been deleted.');
    }


    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
