<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AxoCall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background: #fff;
            max-width: 500px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 32px 24px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .header h2 {
            color: #2a7ae2;
            margin: 0;
        }
        .content {
            font-size: 16px;
            line-height: 1.7;
        }
        .password-box {
            background: #f1f1f1;
            border-radius: 4px;
            padding: 16px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
            margin: 24px 0;
        }
        .footer {
            margin-top: 32px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
        a.button {
            display: inline-block;
            background: #2a7ae2;
            color: #fff !important;
            padding: 12px 28px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 18px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Password Reset</h2>
        </div>
        <div class="content">
            <p>Hi {{ $first_name ?? 'User' }},</p>
            <p>
                Your password has been reset. Please use the following password to log in to your AxoCall account:
            </p>
            <div class="password-box">
                {{ $new_password ?? '********' }}
            </div>
            <p>
                For your security, we recommend changing this password after logging in.
            </p>
            <p>
                <a href="{{ config('app.url') }}/login" class="button">Login to AxoCall</a>
            </p>
            <p>
                If you did not request this password reset, please contact our support team immediately.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AxoCall. All rights reserved.
        </div>
    </div>
</body>
</html>
