<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use App\Models\Menu;
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
            $headerMenus = Menu::with([
                'children' => function ($query) {
                    $query->where('status', 1)
                        ->with([
                            'children' => function ($query) {
                                $query->where('status', 1)
                                    ->with([
                                        'children' => function ($query) {
                                            $query->where('status', 1);
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
                ->whereNull('parent_id') // Ensure only top-level menus are fetched
                ->where('status', 1) // Only include menus with status = 1
                ->where(function ($query) {
                    $query->where('location', 'header')
                        ->orWhere('location', 'both');
                })
                ->get();

            // Optionally, for the footer menus, you can follow the same approach
            $footerMenus = Menu::with([
                'children' => function ($query) {
                    $query->where('status', 1)
                        ->with([
                            'children' => function ($query) {
                                $query->where('status', 1)
                                    ->with([
                                        'children' => function ($query) {
                                            $query->where('status', 1);
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
                ->whereNull('parent_id')
                ->where('status', 1) // Only include menus with status = 1
                ->where(function ($query) {
                    $query->where('location', 'footer')
                        ->orWhere('location', 'both');
                })
                ->get();


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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('front.home');
    }

    public function showLoginForm()
    {
        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id') // Ensure only top-level menus are fetched
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id')
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();
        return view('front.login', [
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus
        ]);
    }

    public function showRegisterForm()
    {
        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id') // Ensure only top-level menus are fetched
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id')
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();

        return view('front.register', [
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus
        ]);
    }
}
