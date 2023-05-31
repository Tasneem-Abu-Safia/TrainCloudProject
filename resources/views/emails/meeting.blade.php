<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Notification</title>
</head>
<body>
<h1>Meeting Notification</h1>
<p>Dear Trainee,</p>

<p>You have a meeting scheduled with your advisor. Here are the details:</p>

<ul>
    <li><strong>Advisor:</strong> {{ $advisor }}</li>
    <li><strong>Date/Time:</strong> {{ $meetingDate }}</li>
    <li><strong>Message:</strong> {{ $emailContent }}</li>
</ul>

<p>Please make sure to attend the meeting at the specified date, time, and location. If you have any questions or need
    to reschedule, please contact your advisor.</p>

<p>Best regards,</p>
<p>Your Training Program</p>
</body>
</html>
