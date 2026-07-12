<?php
require 'app/config/config.php';
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$stmt = $pdo->query('SELECT * FROM student_form_fields');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
