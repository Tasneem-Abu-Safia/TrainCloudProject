<!DOCTYPE html>
<html>
<head>
    <title>Course Activation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        h2 {
            color: #555555;
        }

        p {
            color: #777777;
            line-height: 1.6;
        }

        strong {
            color: #333333;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Course Activation</h2>
    <p>Dear {{ $user->name }},</p>
    <p>Congratulations! You have been accepted into the following course:</p>
    <table>
        <tr>
            <td><strong>Course Name:</strong></td>
            <td>{{ $course->name }}</td>
        </tr>
        <tr>
            <td><strong>Course Number:</strong></td>
            <td>{{ $course->course_num }}</td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td>{{ $course->desc }}</td>
        </tr>
        <tr>
            <td><strong>Duration:</strong></td>
            <td>{{ $course->duration }} {{ $course->duration_unit }}</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>{{ $course->location }}</td>
        </tr>
        <tr>
            <td><strong>Start Date:</strong></td>
            <td>{{ $course->start_date }}</td>
        </tr>
        <tr>
            <td><strong>End Date:</strong></td>
            <td>{{ $course->end_date }}</td>
        </tr>
        <tr>
            <td><strong>Course Fees:</strong></td>
            <td>{{ $course->fees }}</td>
        </tr>
    </table>
    <p>Please make sure to review the course details and prepare for the upcoming sessions.</p>
    <p>If you have any questions or need further assistance, feel free to contact us.</p>
    <p>Best regards,</p>
    <p>Your Organization</p>
</div>
</body>
</html>
