<?php
$source_controller = 'd:\Prince\Edforth\app\controllers\TutorPortalController.php';
$dest_controller = 'd:\Prince\Edforth\app\controllers\StudentPortalController.php';

if (file_exists($source_controller)) {
    $content = file_get_contents($source_controller);
    $content = str_replace('TutorPortalController', 'StudentPortalController', $content);
    $content = str_replace('tutor-portal', 'student-portal', $content);
    $content = str_replace('tutor_portal', 'student_portal', $content);
    // Be careful with replacing 'tutor' -> 'student' blindly, but it should be okay for a copied controller
    $content = str_replace('Tutor', 'Student', $content);
    $content = str_replace('tutor', 'student', $content);
    file_put_contents($dest_controller, $content);
}

$source_dir = 'd:\Prince\Edforth\app\views\tutor_portal';
$dest_dir = 'd:\Prince\Edforth\app\views\student_portal';

if(!is_dir($dest_dir)) mkdir($dest_dir);

$files = scandir($source_dir);
foreach($files as $file) {
    if($file !== '.' && $file !== '..') {
        $file_content = file_get_contents($source_dir . '\\' . $file);
        $file_content = str_replace('tutor-portal', 'student-portal', $file_content);
        $file_content = str_replace('tutor_portal', 'student_portal', $file_content);
        $file_content = str_replace('register-as-tutor', 'register-as-student', $file_content);
        $file_content = str_replace('Tutor', 'Student', $file_content);
        $file_content = str_replace('tutor', 'student', $file_content);
        file_put_contents($dest_dir . '\\' . $file, $file_content);
    }
}
echo "Portal duplicated.\n";
