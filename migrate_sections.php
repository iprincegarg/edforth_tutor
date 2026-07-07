<?php
require_once __DIR__ . '/app/config/config.php';

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Create tutor_sections table
    $sql = "CREATE TABLE IF NOT EXISTS `tutor_sections` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `sectionName` VARCHAR(30) NOT NULL,
      `canDelete` TINYINT(1) DEFAULT 1,
      `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $dbh->exec($sql);
    echo "tutor_sections table created successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
