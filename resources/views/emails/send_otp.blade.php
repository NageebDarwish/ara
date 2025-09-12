<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            font-size: 24px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: #333;
            text-align: center;
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            color: #4CAF50;
            padding: 10px 20px;
            border: 2px dashed #4CAF50;
            display: inline-block;
            margin: 20px 0;
        }

        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 30px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Your OTP Code</h1>
        <p>Hello,</p>
        <p>To complete your registration, please use the following One Time Password (OTP):</p>

        <div class="otp-code">{{ $otp }}</div>

        <p>Please do not share this OTP with anyone. It is valid for only a limited time.</p>

        <p>Thank you for using our service!</p>

        <div class="footer">
            <p>If you did not request this OTP, please contact our <a href="#">support team</a>.</p>
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
