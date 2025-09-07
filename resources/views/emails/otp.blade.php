<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification OTP</title>
    <style>
        /* Reset default styles for email clients */
        body, table, td, p, h1, h2, h3, div {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        /* Main container */
        body {
            background-color: #f1f5f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        /* Content */
        .content {
            padding: 30px;
            background: #f8f9fa;
        }

        .content p {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 20px;
        }

        /* OTP Box */
        .otp-box {
            background: #e5e7eb;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 8px;
            border-radius: 8px;
            color: #1f2937;
            margin: 20px 0;
            border: 1px solid #d1d5db;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 20px;
            }

            .otp-box {
                font-size: 24px;
                letter-spacing: 6px;
                padding: 15px;
            }

            .header h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>Email Verification</h2>
        </div>
        <!-- Content -->
        <div class="content">
            <p>Hello,</p>
            <p>Please use the following One-Time Password (OTP) to verify your email address:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p>This OTP is valid for 10 minutes. If you didn't request this, please ignore this email.</p>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>Best regards,<br>Blue Star Team</p>
        </div>
    </div>
</body>
</html>