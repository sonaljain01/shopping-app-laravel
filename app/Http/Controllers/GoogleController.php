<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if (!$finduser) {
                $finduser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->getId(),
                    // Add any other necessary fields
                ]);
            }
            \Log::info('User from Google:', [$user]);

            Auth::login($finduser);
            return redirect()->route('front.shop');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
