<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
        }
        .details {
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            font-size: 14px;
            color: #777777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <p>Dear {{ $booking->full_name }},</p>

        <p>We are pleased to inform you that your booking has been <strong>approved</strong>.</p>

        <div class="details">
            <p><strong>Event:</strong> {{ $booking->event_name }}</p>
            <p><strong>Date:</strong> {{ $booking->event_date }}</p>
            <p>
                <strong>Time:</strong> 
                {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}
            </p>

        </div>

        <p>Should you have any questions or require further assistance, please do not hesitate to contact us in this number: 09324994185 or on Facebook Page search: J Brothers DBC.</p>

        <p>Thank you for your attention and cooperation.</p>

        <p class="footer">Sincerely,<br>
        The OBSPALO Team</p>
    </div>
</body>
</html>
