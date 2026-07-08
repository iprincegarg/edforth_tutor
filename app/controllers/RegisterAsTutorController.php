<?php
class RegisterAsTutorController extends Controller {
    private $sectionModel;
    private $fieldModel;

    public function __construct() {
        $this->sectionModel = $this->model('Section');
        $this->fieldModel = $this->model('FormField');
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
            'title' => 'Register as Tutor',
            'sections' => $sections,
            'fields' => $formFields
        ];

        $this->view('register_as_tutor/index', $data);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $submissionModel = $this->model('TutorSubmission');
            $formData = [];
            $uploadDir = '../public/uploads/tutors/';

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

            // Process uploaded files
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    if (strpos($key, 'field_') === 0 && $file['error'] == UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'zip'];
                        $maxSize = 5 * 1024 * 1024; // 5MB

                        if (in_array($ext, $allowedExtensions) && $file['size'] <= $maxSize) {
                            $newFilename = uniqid('tutor_') . '_' . time() . '.' . $ext;
                            $destination = $uploadDir . $newFilename;
                            
                            if (move_uploaded_file($file['tmp_name'], $destination)) {
                                $formData[$key] = 'uploads/tutors/' . $newFilename;
                            }
                        }
                    }
                }
            }

            $jsonData = json_encode($formData);
            
            if ($submissionModel->addSubmission($jsonData)) {
                $_SESSION['form_success'] = 'Your application has been successfully submitted!';
            } else {
                $_SESSION['form_error'] = 'An error occurred while submitting your application. Please try again.';
            }

            header('Location: ' . URLROOT . '/register-as-tutor');
            exit;
        } else {
            header('Location: ' . URLROOT . '/register-as-tutor');
            exit;
        }
    }
}
