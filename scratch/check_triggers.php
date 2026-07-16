<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/config.php';
$db = new Database();
$db->query("SHOW TRIGGERS");
$res = $db->resultSet();
print_r($res);
