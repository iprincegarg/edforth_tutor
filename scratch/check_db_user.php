<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/config.php';
$db = new Database();
$db->query("SHOW CREATE TABLE user");
$res = $db->single();
print_r($res);
