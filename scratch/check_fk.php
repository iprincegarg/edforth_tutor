<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/config.php';
$db = new Database();
$db->query("SELECT * FROM information_schema.key_column_usage WHERE referenced_table_name IS NOT NULL AND table_schema = DATABASE()");
print_r($db->resultSet());
