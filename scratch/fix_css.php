<?php
$css_file = 'd:\Prince\Edforth\css\dashboard.css';
$content = file_get_contents($css_file);

// Find the start of tutor form classes
$start_pos = strpos($content, '/* Tutor Form Classes */');

if ($start_pos !== false) {
    $tutor_classes = substr($content, $start_pos);
    $student_classes = str_replace('tutor-', 'student-', $tutor_classes);
    $student_classes = str_replace('Tutor', 'Student', $student_classes);
    
    // Append to file
    file_put_contents($css_file, "\n\n" . $student_classes, FILE_APPEND);
    echo "Appended student classes successfully.\n";
} else {
    echo "Could not find Tutor Form Classes section.\n";
}
