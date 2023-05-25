<!DOCTYPE html>
<html>
<head>
    <title>Account Activation</title>
</head>
<body>
<h2>Account Activation</h2>
<p>Dear {{ $data['user'] }},</p>
<p>Your account has been successfully activated.</p>
<p>Here are your login credentials:</p>
<ul>
    <li><strong>Unique ID:</strong> {{ $data['uniqueId'] }}</li>
    <li><strong>Password:</strong> {{ $data['password'] }}</li>
</ul>
<p>Please keep these credentials safe and secure. You can use them to log in to your account.</p>
<p>Thank you for joining us!</p>
</body>
</html>
