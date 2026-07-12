<?php
$source_controller = 'd:\Prince\Edforth\app\controllers\TutorDashboardController.php';
$dest_controller = 'd:\Prince\Edforth\app\controllers\StudentDashboardController.php';

if (file_exists($source_controller)) {
    $content = file_get_contents($source_controller);
    $content = str_replace('TutorDashboardController', 'StudentDashboardController', $content);
    $content = str_replace('tutor-dashboard', 'student-dashboard', $content);
    $content = str_replace('tutor_portal', 'student_portal', $content);
    $content = str_replace('Tutor', 'Student', $content);
    $content = str_replace('tutor', 'student', $content);
    file_put_contents($dest_controller, $content);
}
echo "Dashboard controller duplicated.\n";
