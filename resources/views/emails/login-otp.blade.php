<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Code</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8fafc; padding:24px;">
    <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:16px; padding:32px; border:1px solid #e2e8f0;">
        <h1 style="font-size:20px; color:#0f172a; margin:0 0 8px;">{{ config('site.name') }}</h1>
        <p style="color:#64748b; font-size:14px; margin:0 0 24px;">
            @if ($purpose === 'register')
                Use this code to complete your registration:
            @else
                Use this code to sign in to your account:
            @endif
        </p>
        <div style="background:#ecfdf8; border:1px solid #ccfbf1; border-radius:12px; padding:20px; text-align:center; margin-bottom:24px;">
            <span style="font-size:32px; font-weight:800; letter-spacing:8px; color:#1a8578;">{{ $otp }}</span>
        </div>
        <p style="color:#64748b; font-size:12px; margin:0;">This code expires in 10 minutes. If you didn't request it, you can ignore this email.</p>
    </div>
</body>
</html>
