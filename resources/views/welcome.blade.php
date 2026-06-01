<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prahari Admin - Welcome</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .hero-section {
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 2.2rem;
            font-weight: 800;
            color: #000;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }
        .logo-wrapper i {
            font-size: 2.5rem;
            color: #2c3e50;
        }
        .subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        .login-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: #2c3e50; /* Aligned with tertiary theme */
            color: #ffffff;
            text-decoration: none;
            padding: 14px 45px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.2);
        }
        .login-btn:hover {
            background-color: #1a252f;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.3);
        }
        .login-btn i {
            font-size: 1.2rem;
        }
        .footer-text {
            position: absolute;
            bottom: 30px;
            font-size: 0.85rem;
            color: #999;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        /* Simple background accent */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #2c3e50, #000);
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="logo-wrapper">
            <i class="bi bi-shield-lock-fill"></i>
            PRAHARI ADMIN
        </div>
        <p class="subtitle">
            Secure administrative gateway for managing cases, challans, and regional oversight. Please log in to access your dashboard.
        </p>
        <a href="{{ route('signin') }}" class="login-btn">
            <i class="bi bi-box-arrow-in-right"></i>
            Login to Dashboard
        </a>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} Prahari Admin System
    </div>
</body>
</html>
