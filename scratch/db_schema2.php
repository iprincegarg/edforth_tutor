<?php
require_once 'd:\Prince\Edforth\app\config\config.php';
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$stmt = $pdo->query('DESC tutors_form');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' - ' . $row['Type'] . ' - ' . $row['Default'] . "\n";
}
