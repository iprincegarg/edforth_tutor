<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();

try {
    $db->query("ALTER TABLE tutors_form ADD COLUMN username VARCHAR(255) NULL, ADD COLUMN raw_password VARCHAR(255) NULL");
    $db->execute();
    echo "Columns added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
