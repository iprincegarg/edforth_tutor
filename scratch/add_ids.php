<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

$db = new Database();
$db->query("SELECT setting_value FROM settings WHERE setting_key = 'front_page_content'");
$row = $db->single();
if ($row) {
    $content = $row->setting_value;
    
    // Add IDs for anchor links
    $content = str_replace('<div class="home">', '<div class="home" id="home">', $content);
    $content = str_replace('<div class="counter">', '<div class="counter" id="about">', $content);
    // Let's also check for contact if there is one. The form is likely in the footer or another section.
    // Let's add ID to testimonial or features for blogs/teach if they exist.
    // The user may manually add these via CMS later if missing, but home and about are key.
    
    $db->query("UPDATE settings SET setting_value = :val WHERE setting_key = 'front_page_content'");
    $db->bind(':val', $content);
    $db->execute();
    echo "DB updated with IDs\n";
}
