<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $db = new Database();

    $db->query("CREATE TABLE IF NOT EXISTS `user_authentication` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `token` varchar(255) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `user_agent` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `last_activity` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `is_active` tinyint(1) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`),
        UNIQUE KEY `token_idx` (`token`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    $db->execute();
    echo "Table 'user_authentication' created successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
