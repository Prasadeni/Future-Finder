<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/Includes/db_connection.php';

$message = '';
$messageType = '';
$validToken = false;
$email = '';
$token = $_GET['token'] ?? '';

if ($token) {
    $tokenHash = hash('sha256', $token);

    // Look up the token AND retrieve the email from DB
    $now = date('Y-m-d H:i:s');   // ← PHP time (same as when token was created)
$stmt = mysqli_prepare($conn,
    "SELECT email FROM password_resets 
     WHERE token = ? AND expires_at > ? 
     LIMIT 1");
mysqli_stmt_bind_param($stmt, 'ss', $tokenHash, $now);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row) {
        $email = $row['email'];
        $validToken = true;
    } else {
        $message = 'This password reset link is invalid or has expired. Please request a new one.';
        $messageType = 'error';
    }
} else {
    $message = 'Invalid reset link. Please request a new password reset.';
    $messageType = 'error';
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($newPassword) < 6) {
        $message = 'Password must be at least 6 characters.';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match.';
        $messageType = 'error';
    } else {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $upd = mysqli_prepare($conn, "UPDATE Users SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($upd, 'ss', $hash, $email);
        $success = mysqli_stmt_execute($upd);
        mysqli_stmt_close($upd);

        if ($success) {
            // Delete token (single-use)
            $del = mysqli_prepare($conn, "DELETE FROM password_resets WHERE email = ?");
            mysqli_stmt_bind_param($del, 's', $email);
            mysqli_stmt_execute($del);
            mysqli_stmt_close($del);

            $message = 'Your password has been reset successfully. You can now log in.';
            $messageType = 'success';
            $validToken = false;
        } else {
            $message = 'Failed to update password. Please try again.';
            $messageType = 'error';
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
    <title>Reset Password | Future Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/home-style.css">
    <link rel="stylesheet" href="CSS/home-responsive.css">
    <link rel="stylesheet" href="CSS/login.css">
    <style>
        body { display: block !important; min-height: 100vh; background: #0d0e3a; padding: 0; margin: 0; }
        .card-wrap { display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 116px); padding: 40px 20px; }
        .card-simple { max-width: 480px; width: 100%; background: #121358; border-radius: 20px; padding: 48px 44px; font-family: 'Poppins', sans-serif; color: #fff; }
        .card-simple h2 { font-size: 26px; font-weight: 800; margin-bottom: 10px; }
        .card-simple .subtitle { font-size: 14px; color: #7f8fc4; margin-bottom: 26px; }
        .card-simple input[type="password"] { width: 100%; padding: 14px 16px; border: 1.4px solid #2F578A; border-radius: 10px; font-size: 14px; color: #fff; outline: none; background: #232F72; margin-bottom: 18px; }
        .card-simple input[type="password"]::placeholder { color: #7f8fc4; }
        .card-simple input[type="password"]:focus { border-color: #36ADA3; box-shadow: 0 0 0 3px rgba(54,173,163,0.20); }
        .card-simple .submit-btn { width: 100%; padding: 14px; border: none; border-radius: 10px; background: #36ADA3; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; }
        .card-simple .back-link { display: block; text-align: center; margin-top: 20px; font-size: 13px; color: #36ADA3; text-decoration: none; }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .alert-success { background: rgba(54,173,163,0.15); border: 1px solid rgba(54,173,163,0.35); color: #5ecfc6; }
        .alert-error { background: rgba(224,68,91,0.15); border: 1px solid rgba(224,68,91,0.35); color: #ff8a9a; }
    </style>
</head>
<body>

    <?php $currentPage = 'reset_password.php'; require_once __DIR__ . '/shared/navbar.php'; ?>

    <div class="card-wrap">
        <div class="card-simple">
            <h2>Reset Your Password</h2>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if ($validToken): ?>
                <p class="subtitle">Choose a new password for <strong><?= htmlspecialchars($email) ?></strong></p>
                <form method="POST">
                    <input type="password" name="new_password" placeholder="New Password (min 6 characters)" required>
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                    <button type="submit" class="submit-btn">Reset Password</button>
                </form>
            <?php elseif ($messageType === 'success'): ?>
                <a href="login.php" class="back-link" style="margin-top:24px;">→ Go to Login</a>
            <?php else: ?>
                <a href="forgot_password.php" class="back-link" style="margin-top:24px;">→ Request a New Link</a>
            <?php endif; ?>

        </div>
    </div>

    <?php require_once __DIR__ . '/shared/footer.php'; ?>
</body>
</html>