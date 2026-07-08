<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

function replaceStatic($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('"/static/', '"https://edforthtutors.com/static/', $content);
        $content = str_replace('\'/static/', '\'https://edforthtutors.com/static/', $content);
        $content = str_replace('url(/static/', 'url(https://edforthtutors.com/static/', $content);
        $content = str_replace('url(\'/static/', 'url(\'https://edforthtutors.com/static/', $content);
        $content = str_replace('url("/static/', 'url("https://edforthtutors.com/static/', $content);
        file_put_contents($file, $content);
        echo "Replaced in $file\n";
    }
}

replaceStatic(__DIR__ . '/../app/views/inc/front_header.php');
replaceStatic(__DIR__ . '/../app/views/inc/front_footer.php');

$db = new Database();
$db->query("SELECT setting_value FROM settings WHERE setting_key = 'front_page_content'");
$row = $db->single();
if ($row) {
    $content = $row->setting_value;
    $content = str_replace('"/static/', '"https://edforthtutors.com/static/', $content);
    $content = str_replace('\'/static/', '\'https://edforthtutors.com/static/', $content);
    $content = str_replace('url(/static/', 'url(https://edforthtutors.com/static/', $content);
    $content = str_replace('url(\'/static/', 'url(\'https://edforthtutors.com/static/', $content);
    $content = str_replace('url("/static/', 'url("https://edforthtutors.com/static/', $content);
    
    $db->query("UPDATE settings SET setting_value = :val WHERE setting_key = 'front_page_content'");
    $db->bind(':val', $content);
    $db->execute();
    echo "Replaced in DB\n";
}
