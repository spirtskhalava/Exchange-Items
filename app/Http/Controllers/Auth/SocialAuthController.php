<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /** Redirect to Google */
    public function redirectToGoogle()
    {
        $callback = route('auth.google.callback');
        return Socialite::driver('google')->redirectUrl($callback)->redirect();
    }

    /** Handle Google callback */
    public function handleGoogleCallback()
    {
        try {
            $callback   = route('auth.google.callback');
            $googleUser = Socialite::driver('google')->redirectUrl($callback)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        // Find or create user by google_id, fall back to email match
        $user = User::where('google_id', $googleUser->getId())->first()
             ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Sync google_id if this was an email-matched existing account
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
            }
            // Pull avatar from Google if none set yet
            if (!$user->avatar && $googleUser->getAvatar()) {
                $user->avatar = $googleUser->getAvatar(); // store URL directly
            }
            $user->save();
        } else {
            // Brand new user via Google
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'password'          => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
                'status'            => 'active',
            ]);
        }

        Auth::login($user, remember: true);

        // Flush any stale "intended" URL (e.g. /login) set before OAuth redirect
        session()->forget('url.intended');

        return redirect(route('products.index'))
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }
}
