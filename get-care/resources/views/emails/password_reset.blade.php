<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Please click the following link to reset your password:</p>
    <p><a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}">Reset Password</a></p>
    <p>This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>