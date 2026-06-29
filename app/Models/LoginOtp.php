<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginOtp extends Model
{
    public const PURPOSE_LOGIN = 'login';

    public const PURPOSE_REGISTER = 'register';

    protected $fillable = [
        'email',
        'otp_hash',
        'purpose',
        'meta',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function hasExceededAttempts(int $max = 5): bool
    {
        return $this->attempts >= $max;
    }
}
