<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $db = new Database();

    // Create user table
    $db->query("CREATE TABLE IF NOT EXISTS `user` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `role` enum('sa','tutor','user') NOT NULL,
        `username` varchar(255) NOT NULL,
        `pass` varchar(255) NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: inactive, 1: active',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table 'user' created successfully.\n";

    // Create logs table
    $db->query("CREATE TABLE IF NOT EXISTS `logs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` int(11) NOT NULL,
        `loggedin` timestamp DEFAULT CURRENT_TIMESTAMP,
        `browser_agent` text,
        `ipaddress` varchar(45) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table 'logs' created successfully.\n";

    // Check if superadmin already exists
    $db->query("SELECT id FROM `user` WHERE `username` = 'admin'");
    $admin = $db->single();

    if (!$admin) {
        // Insert superadmin
        $password = '123456';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $db->query("INSERT INTO `user` (`role`, `username`, `pass`, `status`) VALUES ('sa', 'admin', :pass, 1)");
        $db->bind(':pass', $hashed_password);
        $db->execute();
        echo "Superadmin 'admin' created successfully.\n";
    } else {
        echo "Superadmin 'admin' already exists.\n";
    }

    // Create tutors_form table
    $db->query("CREATE TABLE IF NOT EXISTS `tutors_form` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `form_data` json NOT NULL,
        `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->execute();
    echo "Table 'tutors_form' created successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
