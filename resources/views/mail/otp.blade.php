<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - AxoCall</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-container {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 4px;
            }
            .content {
                padding: 20px 15px;
            }
            .otp-code {
                font-size: 28px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 OTP Verification</h1>
            <p>AxoCall Security Code</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $first_name ?? 'User' }},</h2>
            
            <p>We received a request to verify your identity. Please use the following One-Time Password (OTP) to complete your verification:</p>
            
            <div class="otp-container">
                <h3>Your Verification Code</h3>
                <div class="otp-code">{{ $otp ?? '123456' }}</div>
                <p><strong>Valid until: {{ '5 minutes' }}</strong></p>
            </div>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>This code will expire in {{ '5 minutes' }}</li>
                    <li>Never share this code with anyone</li>
                    <li>Our team will never ask for this code via phone or email</li>
                </ul>
            </div>
            
            <div class="info-box">
                <strong>💡 Didn't request this code?</strong><br>
                If you didn't request this verification code, please ignore this email and ensure your account password is secure.
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $verification_url ?? '#' }}" class="button" target="_blank" style="color: white !important; text-decoration: none !important;">Verify Now</a>
            </p>
            
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #667eea; font-size: 14px;">
                {{ $verification_url }}
            </p>
        </div>
        
        <div class="footer">
            <p><strong>AxoCall Team</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you have any questions, contact our support team.</p>
            <hr style="border: none; border-top: 1px solid #dee2e6; margin: 20px 0;">
            <p style="font-size: 12px; color: #adb5bd;">
                © {{ date('Y') }} AxoCall. All rights reserved.<br>
                This email was sent to {{ $email }}
            </p>
        </div>
    </div>
</body>
</html>
