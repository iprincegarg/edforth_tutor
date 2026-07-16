<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/MailHelper.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing SMTP...\n";
$result = MailHelper::sendCredentials('test@example.com', 'testuser', 'testpass', 'student');
if ($result) {
    echo "Success!\n";
} else {
    echo "Failed.\n";
}
