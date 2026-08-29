<?php
session_start();

// If already logged in, go straight to the assessment
if (isset($_SESSION['user_id'])) {
    header('Location: /future_finder/User/before_assessment.php');
    exit;
}

// Otherwise, show the "Login required" page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Required | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0d0e3a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .restricted-card {
            background: #1a1f7a;
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 460px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .restricted-card .icon {
            font-size: 64px;
            color: #36ada3;
            margin-bottom: 16px;
            display: block;
        }
        .restricted-card h1 {
            font-size: 28px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 8px;
        }
        .restricted-card p {
            font-size: 16px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 32px;
            line-height: 1.6;
        }
        .restricted-card .btn-login {
            display: inline-block;
            padding: 14px 44px;
            background: #36ada3;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            box-shadow: 0 4px 12px rgba(54,173,163,0.3);
        }
        .restricted-card .btn-login:hover {
            background: #2d9992;
            transform: translateY(-2px);
        }
        .restricted-card .btn-login i {
            margin-right: 10px;
        }
        .restricted-card .footer-note {
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }
        .restricted-card .footer-note a {
            color: #36ada3;
            text-decoration: none;
        }
        .restricted-card .footer-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="restricted-card">
        <i class="fas fa-lock icon"></i>
        <h1>Access Restricted</h1>
        <p>You need to be logged in to start the career assessment. Please log in or create an account to continue.</p>
        <a href="/future_finder/login.php" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Go to Login
        </a>
        <div class="footer-note">
            Don't have an account? <a href="/future_finder/login.php">Register now</a>
        </div>
    </div>
</body>
</html>