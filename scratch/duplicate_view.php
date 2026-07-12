<?php
$content = file_get_contents('d:\Prince\Edforth\app\views\settings\tutor_form.php');

$content = str_replace('tutor_form', 'student_form', $content);
$content = str_replace('Tutor', 'Student', $content);
$content = str_replace('tutor', 'student', $content);
$content = str_replace('register-as-tutor', 'register-as-student', $content);

file_put_contents('d:\Prince\Edforth\app\views\settings\student_form.php', $content);
echo "View duplicated.\n";
