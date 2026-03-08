<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    // Google Authentication
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                $user->google_id = $googleUser->getId();
                if (!$user->avatar) {
                    $user->avatar = $googleUser->getAvatar();
                }
                $user->save();
            }
            
            Auth::login($user, true);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'social' => 'Échec de l\'authentification Google. Veuillez réessayer.'
            ]);
        }
    }

    // Facebook Authentication
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = User::where('email', $facebookUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'facebook_id' => $facebookUser->getId(),
                    'email_verified_at' => now(),
                    'avatar' => $facebookUser->getAvatar(),
                ]);
            } else {
                $user->facebook_id = $facebookUser->getId();
                if (!$user->avatar) {
                    $user->avatar = $facebookUser->getAvatar();
                }
                $user->save();
            }
            
            Auth::login($user, true);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            \Log::error('Facebook Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'social' => 'Échec de l\'authentification Facebook. Veuillez réessayer.'
            ]);
        }
    }
}