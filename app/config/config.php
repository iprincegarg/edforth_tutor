<?php
// DB Params
define('DB_HOST', '103.110.127.186');
define('DB_USER', 'edforth');
define('DB_PASS', 'Ireava@001');
define('DB_NAME', 'edforth');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$base_dir = str_replace('/public/', '', $base_dir);
define('URLROOT', $protocol . '://' . $host . $base_dir);
// Site Name
define('SITENAME', 'Edforth Enterprise');
