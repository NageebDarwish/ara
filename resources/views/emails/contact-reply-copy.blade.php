<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy: Reply Sent to Customer</title>
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
            background: linear-gradient(135deg, #28a745, #1e7e34);
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
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #1976d2;
            font-size: 16px;
        }
        .customer-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .reply-sent {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .original-message {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .label {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📋 Admin Copy: Reply Sent</h1>
        </div>
        
        <div class="content">
            <div class="info-box">
                <h4>📧 Reply Successfully Sent</h4>
                <p>A reply has been sent to the customer regarding their contact message. Below is a copy for your records.</p>
            </div>
            
            <div class="customer-info">
                <h4 style="margin-top: 0; color: #495057;">👤 Customer Information</h4>
                <p><span class="label">Name:</span> {{ $recipientName }}</p>
                <p><span class="label">Email:</span> {{ $recipientEmail }}</p>
                <p><span class="label">Date Sent:</span> {{ date('M j, Y g:i A') }}</p>
            </div>
            
            <div class="reply-sent">
                <h4 style="margin-top: 0; color: #155724;">✅ Reply Message Sent:</h4>
                <div style="background: white; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    {!! nl2br(e($replyMessage)) !!}
                </div>
            </div>
            
            <div class="original-message">
                <h4 style="margin-top: 0; color: #856404;">📝 Original Customer Message:</h4>
                <p><span class="label">Subject:</span> {{ $originalSubject }}</p>
                <div style="background: white; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    {{ $originalMessage }}
                </div>
            </div>
            
            <div class="info-box">
                <h4>ℹ️ Note</h4>
                <p>This is an automated copy sent to keep you informed of customer communications. The customer has received the reply message above.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification for admin records.</p>
            <p>&copy; {{ date('Y') }} Arabic All The Time Admin Panel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>