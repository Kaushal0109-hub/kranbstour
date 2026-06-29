<?php

namespace App\Services;

use App\Mail\LoginOtpMail;
use App\Models\LoginOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpAuthService
{
    public const OTP_LENGTH = 6;

    public const OTP_TTL_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    public function send(string $email, string $purpose, array $meta = []): string
    {
        LoginOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->delete();

        $otp = str_pad((string) random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        LoginOtp::create([
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'purpose' => $purpose,
            'meta' => $meta ?: null,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        Mail::to($email)->send(new LoginOtpMail($otp, $purpose));

        return $otp;
    }

    public function verify(string $email, string $otp, string $purpose): ?LoginOtp
    {
        $record = LoginOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $record || $record->isExpired() || $record->hasExceededAttempts(self::MAX_ATTEMPTS)) {
            return null;
        }

        if (! Hash::check($otp, $record->otp_hash)) {
            $record->increment('attempts');

            return null;
        }

        $record->delete();

        return $record;
    }
}
