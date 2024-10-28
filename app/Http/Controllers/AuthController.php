<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Hash;
class AuthController extends Controller
{
    public function login(request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
    
        // Manually specifying 'username' instead of 'email'
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
    
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            return redirect()->route('front.home'); // Redirect to the intended route after login
        }
    
        // If authentication fails, return back with an error
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username', 'remember'));
    }
    public function showLoginForm()
    {
        return view('front.login');
    }
    public function showRegistrationForm()
    {
        return view('front.register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1
        ]);

        // Optionally, log the user in after registration
        Auth::login($user);

        // Redirect to the homepage
        return redirect()->route('front.home')->with('success', 'Account created successfully.');
    }
}

