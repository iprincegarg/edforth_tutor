<?php
$source_controller = 'd:\Prince\Edforth\app\controllers\RegisterAsTutorController.php';
$dest_controller = 'd:\Prince\Edforth\app\controllers\RegisterAsStudentController.php';

$content = file_get_contents($source_controller);
$content = str_replace('RegisterAsTutorController', 'RegisterAsStudentController', $content);
$content = str_replace("model('Section')", "model('StudentSection')", $content);
$content = str_replace("model('FormField')", "model('StudentFormField')", $content);
$content = str_replace("model('TutorSubmission')", "model('StudentSubmission')", $content);
$content = str_replace('register_as_tutor', 'register_as_student', $content);
$content = str_replace('uploads/tutors', 'uploads/students', $content);
$content = str_replace('tutor', 'student', $content);
$content = str_replace('Tutor', 'Student', $content);
file_put_contents($dest_controller, $content);

$source_dir = 'd:\Prince\Edforth\app\views\register_as_tutor';
$dest_dir = 'd:\Prince\Edforth\app\views\register_as_student';
if(!is_dir($dest_dir)) mkdir($dest_dir);

$files = scandir($source_dir);
foreach($files as $file) {
    if($file !== '.' && $file !== '..') {
        $file_content = file_get_contents($source_dir . '\\' . $file);
        $file_content = str_replace('register_as_tutor', 'register_as_student', $file_content);
        $file_content = str_replace('Register as Tutor', 'Register as Student', $file_content);
        $file_content = str_replace('Tutor', 'Student', $file_content);
        $file_content = str_replace('tutor', 'student', $file_content);
        file_put_contents($dest_dir . '\\' . $file, $file_content);
    }
}

// Create uploads directory
$upload_dir = 'd:\Prince\Edforth\public\uploads\students';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

echo "Duplication done.\n";
