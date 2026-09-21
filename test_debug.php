<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔍 Debug Test</h2>";
echo "<pre style='background:#f4f4f4;padding:12px;border:1px solid #ccc;'>";

// ── Step 1: Check if mailer.php exists ──
$mailerPath = __DIR__ . '/Includes/mailer.php';
echo "1️⃣ mailer.php path: $mailerPath\n";
echo "   Exists? " . (file_exists($mailerPath) ? "✅ YES" : "❌ NO") . "\n\n";

// ── Step 2: Check if PHPMailer files exist ──
$phpMailerSrc = __DIR__ . '/PHPMailer-7.1.1/src/';
echo "2️⃣ PHPMailer path: $phpMailerSrc\n";
echo "   PHPMailer.php: " . (file_exists($phpMailerSrc . 'PHPMailer.php') ? "✅ YES" : "❌ NO") . "\n";
echo "   SMTP.php:      " . (file_exists($phpMailerSrc . 'SMTP.php') ? "✅ YES" : "❌ NO") . "\n";
echo "   Exception.php: " . (file_exists($phpMailerSrc . 'Exception.php') ? "✅ YES" : "❌ NO") . "\n\n";

// ── Step 3: Check DB connection ──
require_once __DIR__ . '/Includes/db_connection.php';
echo "3️⃣ DB connection: " . ($conn ? "✅ CONNECTED" : "❌ FAILED") . "\n\n";

// ── Step 4: Check if your email exists in Users table ──
$testEmail = 'chamodiprasadeni@gmail.com';   // ← Change to the email you're testing with
$stmt = mysqli_prepare($conn, "SELECT id, first_name, email FROM Users WHERE email = ?");
mysqli_stmt_bind_param($stmt, 's', $testEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

echo "4️⃣ Searching for: $testEmail\n";
if ($user) {
    echo "   ✅ FOUND — User ID: {$user['id']}, Name: {$user['first_name']}\n";
} else {
    echo "   ❌ NOT FOUND in Users table!\n";
    echo "   👉 Available emails in Users table:\n";
    $all = mysqli_query($conn, "SELECT email FROM Users");
    while ($row = mysqli_fetch_assoc($all)) {
        echo "      - " . $row['email'] . "\n";
    }
}
echo "\n";

// ── Step 5: Check password_resets table ──
$tblCheck = mysqli_query($conn, "SHOW TABLES LIKE 'password_resets'");
echo "5️⃣ password_resets table: " . (mysqli_num_rows($tblCheck) > 0 ? "✅ EXISTS" : "❌ MISSING") . "\n\n";

// ── Step 6: Try loading mailer.php ──
echo "6️⃣ Loading mailer.php...\n";
try {
    require_once __DIR__ . '/Includes/mailer.php';
    echo "   ✅ Loaded successfully\n";
    echo "   sendEmail() exists? " . (function_exists('sendEmail') ? "✅ YES" : "❌ NO") . "\n";
} catch (Throwable $e) {
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// ── Step 7: Try sending a test email ──
echo "7️⃣ Sending test email to: $testEmail\n";
if (function_exists('sendEmail')) {
    $result = sendEmail(
        $testEmail,
        'Test User',
        'Future Finder Test Email',
        '<h1>Test</h1><p>If you see this, PHPMailer works!</p>'
    );
    echo "   Result: " . ($result ? "✅ SENT" : "❌ FAILED") . "\n";
} else {
    echo "   ❌ sendEmail() not available\n";
}

echo "</pre>";