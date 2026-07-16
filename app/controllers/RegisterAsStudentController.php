<?php
class RegisterAsStudentController extends Controller {
    private $sectionModel;
    private $fieldModel;

    public function __construct() {
        $this->sectionModel = $this->model('StudentSection');
        $this->fieldModel = $this->model('StudentFormField');
    }

    public function index() {
        $sections = $this->sectionModel->getSections();
        $allFields = $this->fieldModel->getFieldsWithDetails();
        
        $formFields = [];
        if ($allFields) {
            foreach($allFields as $f) {
                if ($f->show_on_form) {
                    $formFields[] = $f;
                }
            }
        }
        
        $data = [
            'title' => 'Register as Student',
            'sections' => $sections,
            'fields' => $formFields
        ];

        $this->view('register_as_student/index', $data);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $submissionModel = $this->model('StudentSubmission');
            $formData = [];
            $uploadDir = '../public/uploads/students/';

            // Make directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Process POST data
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'field_') === 0) {
                    $formData[$key] = $value;
                }
            }

            // Identify Email and Phone fields
            $emailFieldId = null;
            $phoneFieldId = null;
            $allFields = $this->fieldModel->getFieldsWithDetails();
            if ($allFields) {
                foreach ($allFields as $f) {
                    $nameLower = strtolower($f->field_name);
                    if (strpos($nameLower, 'email') !== false || strpos($nameLower, 'mail') !== false) {
                        $emailFieldId = 'field_' . $f->id;
                    }
                    if (strpos($nameLower, 'phone') !== false || strpos($nameLower, 'mobile') !== false || strpos($nameLower, 'contact') !== false) {
                        $phoneFieldId = 'field_' . $f->id;
                    }
                }
            }

            $submittedEmail = $emailFieldId && isset($formData[$emailFieldId]) ? trim($formData[$emailFieldId]) : '';

            // Unique Check across approved students and tutors (Email only)
            if (!empty($submittedEmail)) {
                $db = new Database();
                $db->query("SELECT form_data FROM students_form WHERE status = 'approved'");
                $studentForms = $db->resultSet();
                
                $db->query("SELECT form_data FROM tutors_form WHERE status = 'approved'");
                $tutorForms = $db->resultSet();
                
                $allApprovedForms = array_merge((array)$studentForms, (array)$tutorForms);
                
                $isDuplicate = false;
                foreach ($allApprovedForms as $form) {
                    $jsonString = strtolower($form->form_data);
                    if (strpos($jsonString, '"' . strtolower($submittedEmail) . '"') !== false) {
                        $isDuplicate = true;
                        break;
                    }
                }

                if ($isDuplicate) {
                    $_SESSION['form_error'] = 'An account with this email already exists and is verified.';
                    header('Location: ' . URLROOT . '/register-as-student');
                    exit;
                }
            }

            // Process uploaded files
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if (strpos($key, 'field_') === 0 && $file['error'] == UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'zip'];
                        $maxSize = 5 * 1024 * 1024; // 5MB

                        if (in_array($ext, $allowedExtensions) && $file['size'] <= $maxSize) {
                            $newFilename = uniqid('student_') . '_' . time() . '.' . $ext;
                            $destination = $uploadDir . $newFilename;
                            
                            if (move_uploaded_file($file['tmp_name'], $destination)) {
                                $formData[$key] = 'uploads/students/' . $newFilename;
                            }
                        }
                    }
                }
            }

            $jsonData = json_encode($formData);
            
            // Generate credentials for the student upfront to save in form table
            $userModel = $this->model('User');
            $username = 'STU' . rand(10000, 99999);
            $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'), 0, 10);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if ($submissionModel->addSubmission($jsonData, $username, $password)) {
                
                // Create user
                $userModel->createUser($username, $hashedPassword, 'student', 1);

                $_SESSION['form_success'] = 'Your application has been successfully submitted!';
                $_SESSION['new_credentials'] = [
                    'username' => $username,
                    'password' => $password
                ];
            } else {
                $_SESSION['form_error'] = 'An error occurred while submitting your application. Please try again.';
            }

            header('Location: ' . URLROOT . '/register-as-student');
            exit;
        } else {
            header('Location: ' . URLROOT . '/register-as-student');
            exit;
        }
    }
}
