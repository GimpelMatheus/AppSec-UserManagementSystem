<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <p>Hi {{ $user->name }},</p>
    <p>Click the link below to verify your email address:</p>
    <a href="{{ $url }}">Verify Email</a>
</body>
</html>
