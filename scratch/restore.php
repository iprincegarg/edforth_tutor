<?php
$html = file_get_contents('d:/Prince/Edforth/edforth.html');

$startStr = '<div class="main_content">';
$endStr = '<footer class="custom-footer">';

$startPos = strpos($html, $startStr);
$endPos = strpos($html, $endStr);

if ($startPos !== false && $endPos !== false) {
    // Also skip the actual '<div class="main_content">' line if needed, but let's just take it all and see
    $content = substr($html, $startPos, $endPos - $startPos);
    echo strlen($content) . " bytes extracted.\n";

    // Update DB
    require 'd:/Prince/Edforth/app/config/config.php';
    require 'd:/Prince/Edforth/app/core/Database.php';

    $db = new Database();
    $db->query("UPDATE settings SET setting_value = :val WHERE setting_key = 'front_page_content'");
    $db->bind(':val', $content);
    if($db->execute()) {
        echo "Database updated successfully!\n";
    } else {
        echo "Failed to update DB.\n";
    }
} else {
    echo "Could not find boundaries.\n";
}
