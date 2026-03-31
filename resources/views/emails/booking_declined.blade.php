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

        <p>We regret to inform you that your booking request has been <strong>declined</strong>.</p>

        <div class="details">
            <p>{{ $reason }}</p>
        </div>

        <div class="details">
            <p><strong>Event/Room:</strong> {{ $booking->event_name }}</p>
            <p><strong>Date:</strong> {{ $booking->event_date }}</p>
            <p><strong>Time:</strong> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</p>
        </div>

        <p>If you have any questions or would like to request an alternative time, please do not hesitate to contact us.</p>

        <p>Thank you for your understanding.</p>

        <p class="footer">Sincerely,<br>
        The OBSPALOTeam</p>
    </div>
</body>
</html>
