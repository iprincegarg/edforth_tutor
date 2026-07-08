<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

$db = new Database();
$db->query("SELECT setting_value FROM settings WHERE setting_key = 'front_page_content'");
$row = $db->single();
if ($row) {
    $content = $row->setting_value;
    
    // We want to shift About after Courses.
    // The structure: 
    // <div class="features" id="features"> ... </div>
    // <div class="courses" id="courses"> ... </div>
    // <div class="testimonial_section"> ... </div>
    // <div class="faq_section"> ... </div>
    // <div class="counter" id="about"> ... </div>
    
    // Actually, "About" is already after "Courses", but it's at the very bottom (after FAQ).
    // Maybe they want About IMMEDIATELY after Courses?
    // Let's extract "About", remove it, and insert it after "Courses".
    
    // The "About" section is `<div class="counter" id="about">`
    preg_match('/<div class="counter" id="about">(.*?)<!-- Footer -->/s', $content . '<!-- Footer -->', $matches);
    if (!empty($matches[0])) {
        // Find the full about section block. Wait, it ends before the next main section.
        // Let's use string operations.
        $about_start = strpos($content, '<div class="counter" id="about">');
        if ($about_start === false) {
             $about_start = strpos($content, '<div class="counter">');
        }

        if ($about_start !== false) {
            // Find end of About (it's the last section before footer in the extracted main_content)
            $about_content = substr($content, $about_start);
            
            // Remove about from the original
            $content = substr($content, 0, $about_start);
            
            // Now insert $about_content after Courses.
            // Courses ends at `<!-- Testimonial Section Start -->` or we can just find it.
            $insert_pos = strpos($content, '<!-- Testimonial Section Start -->');
            if ($insert_pos !== false) {
                $content = substr_replace($content, $about_content . "\n", $insert_pos, 0);
                echo "Shifted About after Courses\n";
            }
        }
    }
    
    $db->query("UPDATE settings SET setting_value = :val WHERE setting_key = 'front_page_content'");
    $db->bind(':val', $content);
    $db->execute();
}
