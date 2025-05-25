<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Account Credentials</title>
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
            background-color: #002b5c;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header img {
            max-width: 180px;
            margin-bottom: 15px;
            background-color: #fff;
            padding: 10px;
            border-radius: 4px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }
        .credentials h3 {
            margin-top: 0;
            color: #002b5c;
            font-size: 18px;
        }
        .credentials p {
            margin: 12px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #002b5c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #001d3d;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer a {
            color: #002b5c;
            text-decoration: none;
        }
        .contact-info {
            margin-top: 15px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/nbc-logo.png') }}" alt="NBC Tanzania Logo">
        <h2>Welcome to NBC Saccos & Microfinance Management System</h2>
    </div>

    <div class="content">
        <p>Hello {{ $name }},</p>

        <p>We're excited to let you know that your account has been successfully created. Below are your login credentials:</p>

        <div class="credentials">
            <h3>Your Login Credentials:</h3>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>For your security, please change your password after your first login.</p>

        <div class="button-container">
            <a href="{{ $url }}" class="button">Login to Your Account</a>
        </div>

        <p>If you have any questions or need help, our support team is here for you.</p>
    </div>

    <div class="footer">
        <p>This is an automated email, please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} NBC Tanzania. All rights reserved.</p>
        <div class="contact-info">
            <a href="https://www.nbc.co.tz">www.nbc.co.tz</a> | 
            Tel: +255 22 219 3000 | 
            <a href="mailto:contact.centre@nbc.co.tz">contact.centre@nbc.co.tz</a>
        </div>
    </div>
</body>
</html>
