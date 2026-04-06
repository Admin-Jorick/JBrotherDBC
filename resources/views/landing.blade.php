<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBSPALO - Landing</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="{{ asset('image/JBlogo.jpg') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background: url('/Rams/Background1.png') no-repeat center center/cover;
        }

        /* Dark Navy overlay */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(11, 29, 42, 0.95),
                rgba(13, 110, 253, 0.65)
            );
        }

        .container {
            position: relative;
            text-align: center;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            padding: 45px 55px;
            border-radius: 22px;
            color: #ffffff;
            max-width: 520px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.45);
            animation: fadeIn 1.2s ease;
        }

        .container h1 {
            font-weight: 600;
            margin-bottom: 12px;
        }

        .container p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 32px;
        }

        .btn-continue {
            display: inline-block;
            padding: 12px 38px;
            background: #0d6efd;
            color: #ffffff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            background: #0b5ed7;
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.35);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome to OBSPALO</h1>
        <p>Online Booking System for Performing Arts in Local Organizations</p>

        <a href="/home" class="btn-continue">Continue</a>
    </div>

</body>
</html>
