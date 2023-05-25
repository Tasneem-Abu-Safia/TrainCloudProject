<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
<h2>Forgot Password</h2>
<p>Dear {{ $data['user'] }},</p>
<p>We have received a request to reset your password.</p>
<p>Your new login credentials are:</p>
<ul>
    <li><strong>Unique ID:</strong> {{ $data['uniqueId'] }}</li>
    <li><strong>New Password:</strong> {{ $data['password'] }}</li>
</ul>
<p>Please keep these credentials safe and secure. You can use them to log in to your account.</p>
<p>If you didn't request this password reset, please ignore this email.</p>
<p>Thank you!</p>
</body>
</html>
