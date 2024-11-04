<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {

        try {
            // Create the user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 1, 
            ]);

            
            if (!$user) {
                return back()->with('error', 'Something went wrong');
            }

            // Optionally log in the user
            Auth::login($user);

            return redirect()->route('front.home')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            \Log::error('User registration failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating your account. Please try again.');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('front.home');
        } else {
            return back()->with('error', 'Invalid email or password');
        }
    }
}
