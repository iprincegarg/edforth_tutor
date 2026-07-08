<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query("UPDATE tutors_form SET username = 'prince', raw_password = 'password' WHERE id = 1");
$db->execute();
echo "Updated tutors_form for id 1.\n";
