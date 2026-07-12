<?php
require_once 'd:\Prince\Edforth\app\config\config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. student_sections
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `student_sections` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sectionName` varchar(30) NOT NULL,
            `canDelete` tinyint(1) DEFAULT 1,
            `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
            `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `sequence_id` int(11) DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 2. student_form_fields (No filter_id)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `student_form_fields` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `section_id` int(11) NOT NULL,
            `field_name` varchar(30) NOT NULL,
            `field_type` varchar(20) NOT NULL,
            `char_limit` int(11) DEFAULT NULL,
            `placeholder_text` varchar(100) DEFAULT NULL,
            `field_values` text DEFAULT NULL,
            `is_required` tinyint(1) DEFAULT 0,
            `show_on_form` tinyint(1) DEFAULT 1,
            `show_to_user` tinyint(1) DEFAULT 1,
            `sequence_id` int(11) DEFAULT 0,
            `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 3. students_form
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `students_form` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `form_data` longtext NOT NULL,
            `status` enum('pending','approved','rejected') DEFAULT 'pending',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `username` varchar(255) DEFAULT NULL,
            `raw_password` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    echo "Tables created successfully.\n";

} catch (PDOException $e) {
    echo 'Migration failed: ' . $e->getMessage();
}
