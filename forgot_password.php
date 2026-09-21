<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/Includes/db_connection.php';
require_once __DIR__ . '/Includes/mailer.php';

$message = '';
$messageType = ''; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        // Check if user exists
        $stmt = mysqli_prepare($conn, "SELECT id, first_name FROM Users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Always show the same message for security (don't reveal if email exists)
        $message = 'If an account exists with this email, a password reset link has been sent.';
        $messageType = 'success';

        if ($user) {
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Delete any old tokens for this email
            $del = mysqli_prepare($conn, "DELETE FROM password_resets WHERE email = ?");
            mysqli_stmt_bind_param($del, 's', $email);
            mysqli_stmt_execute($del);
            mysqli_stmt_close($del);

            // Insert new token
            $ins = mysqli_prepare($conn, "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($ins, 'sss', $email, $tokenHash, $expiresAt);
            mysqli_stmt_execute($ins);
            mysqli_stmt_close($ins);

            // Build reset link
            $resetLink = "http://localhost/future_finder/reset_password.php?token=" . $token;
            // Email body
            $body = "
            <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:24px;border:1px solid #eee;border-radius:10px;'>
                <h2 style='color:#36ada3;'>Future Finder – Password Reset</h2>
                <p>Hi " . htmlspecialchars($user['first_name']) . ",</p>
                <p>We received a request to reset your password. Click the button below to create a new password:</p>
                <p style='text-align:center;margin:28px 0;'>
                    <a href='$resetLink' style='background:#36ada3;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:bold;'>
                        Reset My Password
                    </a>
                </p>
                <p style='font-size:13px;color:#666;'>Or copy and paste this link into your browser:</p>
                <p style='font-size:12px;color:#36ada3;word-break:break-all;'>$resetLink</p>
                <hr style='border:none;border-top:1px solid #eee;margin:24px 0;'>
                <p style='font-size:12px;color:#999;'>This link will expire in 1 hour. If you did not request a password reset, please ignore this email.</p>
                <p style='font-size:12px;color:#999;'>— Future Finder Team</p>
            </div>";

            sendEmail($email, $user['first_name'], 'Reset Your Future Finder Password', $body);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/home-style.css">
    <link rel="stylesheet" href="CSS/home-responsive.css">
    <link rel="stylesheet" href="CSS/login.css">
    <style>
        body {
            display: block !important;
            align-items: unset !important;
            justify-content: unset !important;
            min-height: 100vh;
            background: #0d0e3a;
            padding: 0;
            margin: 0;
        }
        .card-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 116px);
            padding: 40px 20px;
        }
        .card-simple {
            max-width: 480px;
            width: 100%;
            background: #121358;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(18, 19, 88, 0.40);
            padding: 48px 44px;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }
        .card-simple h2 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .card-simple .subtitle {
            font-size: 14px;
            color: #7f8fc4;
            margin-bottom: 26px;
            line-height: 1.6;
        }
        .card-simple .field {
            position: relative;
            margin-bottom: 20px;
        }
        .card-simple input[type="email"] {
            width: 100%;
            padding: 14px 16px;
            border: 1.4px solid #2F578A;
            border-radius: 10px;
            font-size: 14px;
            color: #ffffff;
            outline: none;
            background: #232F72;
        }
        .card-simple input[type="email"]::placeholder { color: #7f8fc4; }
        .card-simple input[type="email"]:focus {
            border-color: #36ADA3;
            box-shadow: 0 0 0 3px rgba(54, 173, 163, 0.20);
        }
        .card-simple .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #36ADA3;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .card-simple .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(54, 173, 163, 0.40);
        }
        .card-simple .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #36ADA3;
            text-decoration: none;
        }
        .card-simple .back-link:hover { text-decoration: underline; }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .alert-success {
            background: rgba(54, 173, 163, 0.15);
            border: 1px solid rgba(54, 173, 163, 0.35);
            color: #5ecfc6;
        }
        .alert-error {
            background: rgba(224, 68, 91, 0.15);
            border: 1px solid rgba(224, 68, 91, 0.35);
            color: #ff8a9a;
        }
    </style>
</head>
<body>

    <?php
        $currentPage = 'forgot_password.php';
        require_once __DIR__ . '/shared/navbar.php';
    ?>

    <div class="card-wrap">
        <div class="card-simple">
            <h2>Forgot Password?</h2>
            <p class="subtitle">Enter your registered email address and we'll send you a link to reset your password.</p>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($messageType !== 'success'): ?>
            <form method="POST" action="">
                <div class="field">
                    <input type="email" name="email" placeholder="Your Email Address" required>
                </div>
                <button type="submit" class="submit-btn">Send Reset Link</button>
            </form>
            <?php endif; ?>

            <a href="login.php" class="back-link">← Back to Login</a>
        </div>
    </div>

    <?php require_once __DIR__ . '/shared/footer.php'; ?>

</body>
</html>