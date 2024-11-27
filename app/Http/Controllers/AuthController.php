<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use App\Models\Menu;
use Http;
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
                'phone_number' => $request->phone_number,
                'country_code' => $request->country_code,
                'country' => $request->country
            ]);

            if (session('utm')) {
                $user->update(session('utm'));
            }
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
        session()->flush();
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


        // $ip = request()->ip();
        // $country = $this->getLocationInfo($ip);
        
        // $data = $this->getLocationInfo($ip);
        // $telcode = $this->getTelCode($data['data']['country'] ?? 'IN');
        $country = session('country', 'IN');
        if (auth()->check()) {
            $country = auth()->user()->country ?? $country;
        } elseif (! session('country')) {
            $ip = request()->ip() ?? '146.70.245.84';
            $data = getLocationInfo($ip);
            $country = $data['data']['country'] ?? $country;
        }
        $telcode = getTelCode($country)['code'];
        return view('front.register', [
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus,
            'telcode' => $telcode
        ]);
    }

    // protected function getTelCode($data)
    // {
    //     $response = Http::get("https://restcountries.com/v3.1/alpha/{$data}");
    //     if ($response->ok()) {
    //         $data = $response->json();
    //         $telephoneCode = $data[0]['idd']['root'].$data[0]['idd']['suffixes'][0];
    //         return [
    //             'code' => $telephoneCode,
    //             'status' => true,
    //         ];
    //     } else {
    //         return [
    //             'code' => 'Country not found.',
    //             'status' => false,
    //         ];
    //     }
    // }
    // protected function getLocationInfo(string $ip): array
    // {
    //     try {
    //         $response = Http::get("http://ipinfo.io/{$ip}/json");
    //         if ($response->successful()) {
    //             $data = $response->json();
    //             if (isset($data['bogon']) && $data['bogon'] == 1) {
    //                 return [
    //                     'status' => 'false',
    //                     'message' => 'Bogon IP address detected. Unable to determine location.',
    //                 ];
    //             }
    //             return [
    //                 'status' => 'true',
    //                 'data' => $data,
    //             ];
    //         }
    //         return [
    //             'status' => 'false',
    //             'message' => 'Unable to retrieve location data.',
    //         ];
    //     } catch (\Throwable $th) {
    //         return [
    //             'status' => 'false',
    //             'message' => 'An error occurred while fetching location data.',
    //         ];
    //     }
    // }
}
