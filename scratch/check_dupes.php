<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/config.php';
$db = new Database();
$db->query("SELECT username, COUNT(*) as count FROM user GROUP BY username HAVING count > 1");
print_r($db->resultSet());
