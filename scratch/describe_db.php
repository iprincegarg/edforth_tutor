<?php
require 'app/config/config.php';
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$stmt = $pdo->query('DESCRIBE students_form');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
