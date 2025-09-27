<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your Message</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .original-message {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .original-message h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .reply-message {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 Reply to Your Message</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $recipientName }},
            </div>
            
            <p>Thank you for contacting us. We have received your message and here is our reply:</p>
            
            <div class="reply-message">
                {!! nl2br(e($replyMessage)) !!}
            </div>
            
            <div class="original-message">
                <h4>Your Original Message:</h4>
                <p><strong>Subject:</strong> {{ $originalSubject }}</p>
                <p><strong>Message:</strong></p>
                <p>{{ $originalMessage }}</p>
            </div>
            
            <p>If you have any further questions or need additional assistance, please don't hesitate to contact us.</p>
            
            <div class="signature">
                <p>Best regards,<br>
                <strong>Arabic All The Time Team</strong><br>
                Support Team</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This email was sent in response to your inquiry. Please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} Arabic All The Time. All rights reserved.</p>
        </div>
    </div>
</body>
</html>