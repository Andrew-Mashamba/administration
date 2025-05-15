<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Your SACCO Instance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Your SACCO Instance</h1>
    </div>

    <div class="content">
        <p>Dear {{ $name }},</p>

        <p>Welcome to your new SACCO instance! Your account has been successfully created and is ready to use.</p>

        <div class="credentials">
            <h3>Your Login Credentials:</h3>
            <p><strong>URL:</strong> <a href="{{ $url }}">{{ $url }}</a></p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>For security reasons, we recommend changing your password after your first login.</p>

        <a href="{{ $url }}" class="button">Access Your SACCO</a>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>
        The SACCO Administration Team</p>
    </div>

    <div class="footer">
        <p>This is an automated message, please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} SACCO Administration. All rights reserved.</p>
    </div>
</body>
</html> 