<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/config/config.php';
$db = new Database();
$db->query("SELECT * FROM students_form");
$res = $db->resultSet();
print_r($res);
$db->query("SELECT * FROM user");
$users = $db->resultSet();
print_r($users);
