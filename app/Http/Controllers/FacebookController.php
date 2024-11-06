<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Auth;
class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();

            if (!$finduser) {
                $username = $user->getName() ?: 'user_' . $user->getId();
                $finduser = User::create([
                    'username' => $username,
                    'email' => $user->getEmail(),
                    'facebook_id' => $user->getId(),
                    // Add any other necessary fields
                ]);
            }

            Auth::login($finduser, true);
            return redirect()->route('front.shop');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
