<?php
require_once APPROOT . '/libraries/PHPMailer/Exception.php';
require_once APPROOT . '/libraries/PHPMailer/PHPMailer.php';
require_once APPROOT . '/libraries/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    private static function getMailer() {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 's3.cpaneIhost.co.in';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@edforthtutors.com';
            $mail->Password   = 'Noreply@#01';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('no-reply@edforthtutors.com', 'EdForth');
            $mail->isHTML(true);
            
            return $mail;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function sendCredentials($toEmail, $username, $password, $role) {
        $mail = self::getMailer();
        if (!$mail) return false;

        $loginUrl = ($role === 'tutor') ? URLROOT . '/tutor-portal' : URLROOT . '/student-portal';
        $roleName = ucfirst($role);

        $subject = "Your EdForth $roleName Account Created Successfully - Login Credentials";
        
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
            <div style='background-color: #0f172a; padding: 20px; text-align: center; color: white;'>
                <h2 style='margin: 0;'>Welcome to EdForth</h2>
            </div>
            <div style='padding: 30px;'>
                <p>Hello,</p>
                <p>Your <strong>$roleName</strong> account has been successfully created and approved.</p>
                <p>You can now log in to the portal using the following credentials:</p>
                
                <div style='background-color: #f8fafc; padding: 15px; border-left: 4px solid #3b82f6; margin: 20px 0;'>
                    <p style='margin: 0 0 10px 0;'><strong>Username:</strong> $username</p>
                    <p style='margin: 0;'><strong>Password:</strong> $password</p>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$loginUrl' style='background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;'>Login to Portal</a>
                </div>
                
                <p>If you have any questions, please contact our support team.</p>
                <p>Best Regards,<br>The EdForth Team</p>
            </div>
        </div>";

        try {
            $mail->addAddress($toEmail);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '</div>', '</p>'], "\n", $body));

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
