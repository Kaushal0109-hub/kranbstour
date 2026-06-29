<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\GoogleAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(Request $request): RedirectResponse
    {
        if (! GoogleAuth::isConfigured()) {
            return redirect()->route('login')->with('error', 'Google sign-in is not configured. Add credentials in Admin → Master → Google Login.');
        }

        $intent = $request->query('intent', 'login');
        if (! in_array($intent, ['login', 'register'], true)) {
            $intent = 'login';
        }

        $request->session()->put('google_auth_intent', $intent);

        return $this->googleDriver()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        if (! GoogleAuth::isConfigured()) {
            return redirect()->route('login')->with('error', 'Google sign-in is not configured.');
        }

        try {
            $googleUser = $this->googleDriver()->user();
        } catch (InvalidStateException) {
            return redirect()->route('login')->with('error', 'Google sign-in session expired. Please try again.');
        } catch (Throwable $e) {
            Log::warning('Google OAuth failed', ['message' => $e->getMessage()]);

            $message = config('app.debug')
                ? 'Google sign-in failed: '.$e->getMessage()
                : 'Google sign-in was cancelled or failed. Please try again.';

            return redirect()->route('login')->with('error', $message);
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));

        if ($email === '') {
            return redirect()->route('login')->with('error', 'Google did not provide an email address.');
        }

        $intent = $request->session()->pull('google_auth_intent', 'login');
        $isNewUser = false;

        $user = User::query()->where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                if ($user->isAdmin()) {
                    return redirect()->route('login')->with('error', 'This email is registered as admin. Use the admin login page.');
                }

                $user->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                $isNewUser = true;
                $user = User::create([
                    'name' => $googleUser->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'password' => Str::password(32),
                    'role' => User::ROLE_CUSTOMER,
                    'email_verified_at' => now(),
                ]);
            }
        }

        if ($user->isAdmin()) {
            return redirect()->route('login')->with('error', 'Please use the admin login page.');
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        $message = ($isNewUser || $intent === 'register')
            ? 'Welcome to '.config('site.name').'!'
            : 'Welcome back!';

        return redirect()->intended(route('dashboard.index'))->with('success', $message);
    }

    private function googleDriver()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirectUrl(GoogleAuth::redirectUri());
    }
}
