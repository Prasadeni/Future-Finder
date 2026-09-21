

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer-7.1.1/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-7.1.1/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer-7.1.1/src/Exception.php';

function sendEmail($toEmail, $toName, $subject, $bodyHTML) {
    // ── Gmail SMTP Configuration ──
    $gmailUser = 'chamodiprasadenidesilva@gmail.com';     // ← YOUR Gmail address
    $gmailPass = 'whpf gdpb lxta qhix';         // ← YOUR 16-char App Password

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $gmailUser;
        $mail->Password   = $gmailPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        $mail->setFrom($gmailUser, 'Future Finder');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHTML;
        $mail->AltBody = strip_tags($bodyHTML);

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "<div style='background:#ffe;padding:12px;border:1px solid #e00;font-family:monospace;'>";
        echo "<strong>MAILER ERROR:</strong> " . htmlspecialchars($mail->ErrorInfo);
        echo "</div>";
        return false;
    }
}