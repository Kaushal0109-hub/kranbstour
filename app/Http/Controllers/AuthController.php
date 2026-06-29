<?php

namespace App\Http\Controllers;

use App\Models\LoginOtp;
use App\Models\User;
use App\Services\OtpAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private OtpAuthService $otpAuth) {}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function sendLoginOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower($validated['email']);
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput()
                ->with('error', 'No account found with this email. Please sign up first.');
        }

        if ($user->isAdmin()) {
            return back()
                ->withInput()
                ->with('error', 'This email is registered as admin. Please use the admin login page.');
        }

        $otp = $this->otpAuth->send($email, LoginOtp::PURPOSE_LOGIN);

        $request->session()->put('otp_flow', LoginOtp::PURPOSE_LOGIN);
        $request->session()->put('otp_email', $email);

        return $this->redirectToVerify($request, $otp, 'We sent a 6-digit code to your email.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function sendRegisterOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $email = strtolower($validated['email']);

        $otp = $this->otpAuth->send($email, LoginOtp::PURPOSE_REGISTER, [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $request->session()->put('otp_flow', LoginOtp::PURPOSE_REGISTER);
        $request->session()->put('otp_email', $email);
        $request->session()->put('otp_name', $validated['name']);
        $request->session()->put('otp_phone', $validated['phone'] ?? null);

        return $this->redirectToVerify($request, $otp, 'We sent a 6-digit code to verify your email.');
    }

    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('otp_email') || ! $request->session()->has('otp_flow')) {
            return redirect()->route('login')->with('error', 'Please enter your email to receive a login code.');
        }

        return view('auth.verify-otp', [
            'email' => $request->session()->get('otp_email'),
            'flow' => $request->session()->get('otp_flow'),
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('otp_email');
        $flow = $request->session()->get('otp_flow');

        if (! $email || ! $flow) {
            return redirect()->route('login')->with('error', 'Your verification session expired. Please try again.');
        }

        $record = $this->otpAuth->verify($email, $validated['otp'], $flow);

        if (! $record) {
            return back()->with('error', 'Invalid or expired code. Please try again.');
        }

        if ($flow === LoginOtp::PURPOSE_LOGIN) {
            $user = User::query()->where('email', $email)->first();

            if (! $user || $user->isAdmin()) {
                $this->clearOtpSession($request);

                return redirect()->route('login')->with('error', 'Unable to sign in. Please try again.');
            }

            Auth::login($user, remember: true);
        } else {
            $meta = $record->meta ?? [];
            $user = User::create([
                'name' => $meta['name'] ?? $request->session()->get('otp_name', 'Customer'),
                'email' => $email,
                'phone' => $meta['phone'] ?? $request->session()->get('otp_phone'),
                'password' => Str::password(32),
                'role' => User::ROLE_CUSTOMER,
                'email_verified_at' => now(),
            ]);

            Auth::login($user, remember: true);
        }

        $request->session()->regenerate();
        $this->clearOtpSession($request);

        $message = $flow === LoginOtp::PURPOSE_REGISTER
            ? 'Welcome to '.config('site.name').'!'
            : 'Welcome back!';

        return redirect()->intended(route('dashboard.index'))->with('success', $message);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_email');
        $flow = $request->session()->get('otp_flow');

        if (! $email || ! $flow) {
            return redirect()->route('login')->with('error', 'Please start again.');
        }

        if ($flow === LoginOtp::PURPOSE_LOGIN) {
            $user = User::query()->where('email', $email)->first();

            if (! $user || $user->isAdmin()) {
                return redirect()->route('login')->with('error', 'Unable to resend code.');
            }

            $otp = $this->otpAuth->send($email, LoginOtp::PURPOSE_LOGIN);
        } else {
            $otp = $this->otpAuth->send($email, LoginOtp::PURPOSE_REGISTER, [
                'name' => $request->session()->get('otp_name'),
                'phone' => $request->session()->get('otp_phone'),
            ]);
        }

        return $this->redirectToVerify($request, $otp, 'A new code has been sent to your email.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectToVerify(Request $request, string $otp, string $message): RedirectResponse
    {
        $redirect = redirect()->route('auth.verify-otp')->with('success', $message);

        if (config('app.debug')) {
            $redirect->with('dev_otp', $otp);
        }

        return $redirect;
    }

    private function clearOtpSession(Request $request): void
    {
        $request->session()->forget(['otp_flow', 'otp_email', 'otp_name', 'otp_phone', 'dev_otp']);
    }
}
