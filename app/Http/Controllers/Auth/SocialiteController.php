<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login with ' . ucfirst($provider) . ', please try again.']);
        }

        $user = User::where($provider . '_id', $socialUser->getId())
                    ->orWhere('email', $socialUser->getEmail())
                    ->first();

        if ($user) {
            // Update existing user with social ID if not present
            if (!$user->{$provider . '_id'}) {
                $user->update([
                    $provider . '_id' => $socialUser->getId(),
                    'avatar_url' => $socialUser->getAvatar(),
                ]);
            }
            
            Auth::login($user);
        } else {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)), // Random secure password
                $provider . '_id' => $socialUser->getId(),
                'avatar_url' => $socialUser->getAvatar(),
                'email_verified_at' => now(), // Auto-verify email from trusted providers
            ]);

            Auth::login($user);
        }

        return redirect()->route('dashboard');
    }
}
