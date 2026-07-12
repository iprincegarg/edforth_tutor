<?php
require 'app/config/config.php';
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);

// Add is_deletable to tutor_form_fields
try {
    $pdo->exec("ALTER TABLE tutor_form_fields ADD COLUMN is_deletable TINYINT(1) DEFAULT 1");
    echo "Added is_deletable to tutor_form_fields\n";
} catch (Exception $e) {
    echo "Column likely already exists or error: " . $e->getMessage() . "\n";
}

// Add is_deletable to student_form_fields
try {
    $pdo->exec("ALTER TABLE student_form_fields ADD COLUMN is_deletable TINYINT(1) DEFAULT 1");
    echo "Added is_deletable to student_form_fields\n";
} catch (Exception $e) {
    echo "Column likely already exists or error: " . $e->getMessage() . "\n";
}

// Set Email fields to not deletable
$pdo->exec("UPDATE tutor_form_fields SET is_deletable = 0 WHERE LOWER(field_name) LIKE '%email%' OR LOWER(field_name) LIKE '%mail%'");
$pdo->exec("UPDATE student_form_fields SET is_deletable = 0 WHERE LOWER(field_name) LIKE '%email%' OR LOWER(field_name) LIKE '%mail%'");

echo "DB migration complete.\n";
