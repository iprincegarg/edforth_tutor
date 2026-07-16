<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/libraries/PHPMailer/Exception.php';
require_once __DIR__ . '/../app/libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../app/libraries/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER
    $mail->isSMTP();
    $mail->Host       = 's3.cpanelhost.co.in';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no-reply@edforthtutors.com';
    $mail->Password   = 'Noreply@#01';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    
    $mail->setFrom('no-reply@edforthtutors.com', 'EdForth');
    $mail->addAddress('princegarg1234@gmail.com');
    $mail->Subject = 'Test Email with Debug';
    $mail->Body = 'This is a test email to verify SMTP routing.';
    
    $mail->send();
    echo "Mail Sent!\n";
} catch (Exception $e) {
    echo "Mail Failed: " . $e->getMessage() . "\n";
}
