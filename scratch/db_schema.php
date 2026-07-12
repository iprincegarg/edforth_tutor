<?php
require_once 'd:\Prince\Edforth\app\config\config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['tutor_sections', 'tutor_filters', 'tutor_form_fields', 'tutor_submissions'];
    
    foreach ($tables as $table) {
        echo "=== $table ===\n";
        $stmt = $pdo->query("DESC $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']} - {$row['Extra']}\n";
        }
        echo "\n";
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
