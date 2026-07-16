<?php
class StudentsController extends Controller {
    private $submissionModel;
    private $userModel;
    private $fieldModel;

    private $filterModel;

    public function __construct() {
        if(!isLoggedIn()) {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
        $this->submissionModel = $this->model('StudentSubmission');
        $this->userModel = $this->model('User');
        $this->fieldModel = $this->model('StudentFormField');
        require_once APPROOT . '/helpers/MailHelper.php';
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $st_page = isset($_GET['st_page']) ? (int)$_GET['st_page'] : 1;
        $s_page = isset($_GET['s_page']) ? (int)$_GET['s_page'] : 1;
        if ($st_page < 1) $st_page = 1;
        if ($s_page < 1) $s_page = 1;

        // Collect active filter selections (field_id => value)
        $activeFilters = [];
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'filter_field_') === 0 && !empty($value)) {
                $fieldId = str_replace('filter_field_', '', $key);
                $activeFilters[(int)$fieldId] = $value;
            }
        }

        $limit = 25;
        $t_offset = ($st_page - 1) * $limit;
        $s_offset = ($s_page - 1) * $limit;

        $studentSubmissions = $this->submissionModel->getPaginatedSubmissions('processed', $search, $limit, $t_offset);
        $totalStudents = $this->submissionModel->getTotalSubmissions('processed', $search);
        
        $pendingSubmissions = $this->submissionModel->getPaginatedSubmissions('pending', $search, $limit, $s_offset);
        $totalPending = $this->submissionModel->getTotalSubmissions('pending', $search);

        $fields = $this->fieldModel->getFieldsWithDetails();

        // Extract fields that have a filter assigned (for the filter dropdowns)
        $filterFields = array_filter($fields, function($f) {
            return !empty($f->filter_id) && !empty($f->filterValues);
        });
        
        $data = [
            'title' => 'Student Applications',
            'studentSubmissions' => $studentSubmissions,
            'totalStudents' => $totalStudents,
            'st_page' => $st_page,
            'st_totalPages' => ceil($totalStudents / $limit),
            'pendingSubmissions' => $pendingSubmissions,
            'totalPending' => $totalPending,
            's_page' => $s_page,
            's_totalPages' => ceil($totalPending / $limit),
            'search' => $search,
            'activeFilters' => $activeFilters,
            'fields' => $fields,
            'filterFields' => array_values($filterFields),
            'success_msg' => $_SESSION['success_msg'] ?? '',
            'field_err' => $_SESSION['field_err'] ?? ''
        ];

        unset($_SESSION['success_msg']);
        unset($_SESSION['field_err']);

        $this->view('students/index', $data);
    }

    public function approve_submission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if(empty($username) || empty($password)) {
                $_SESSION['field_err'] = 'Username and password are required to approve the student.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $userExists = $this->userModel->getUserByUsername($username);
                if($userExists) {
                    $userCreated = $this->userModel->updateUserCredentials($username, $username, $hashedPassword);
                } else {
                    $userCreated = $this->userModel->createUser($username, $hashedPassword, 'student', 1);
                }
                
                if($userCreated) {
                    $this->submissionModel->updateStatusAndCredentials($id, 'approved', $username, $password);
                    
                    // Try to send email
                    $submission = $this->submissionModel->getSubmissionById($id);
                    $fields = $this->fieldModel->getFieldsWithDetails();
                    $emailFieldId = null;
                    foreach ($fields as $field) {
                        if (stripos($field->field_name, 'email') !== false) {
                            $emailFieldId = 'field_' . $field->id;
                            break;
                        }
                    }
                    $toEmail = null;
                    if ($submission && $emailFieldId) {
                        $formData = json_decode($submission->form_data, true);
                        if (isset($formData[$emailFieldId])) {
                            $toEmail = $formData[$emailFieldId];
                        }
                    }
                    
                    if ($toEmail && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                        MailHelper::sendCredentials($toEmail, $username, $password, 'student');
                        $_SESSION['success_msg'] = 'Student approved, account created, and email sent successfully!';
                    } else {
                        $_SESSION['success_msg'] = 'Student approved and account created successfully! (Could not send email: no valid email field found)';
                    }
                } else {
                    $_SESSION['field_err'] = 'Failed to create student account.';
                }
            }
        }
        header('Location: ' . URLROOT . '/students');
        exit;
    }

    public function change_credentials() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $oldUsername = $_POST['old_username'] ?? '';
            $newUsername = $_POST['new_username'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if(empty($newUsername) || empty($newPassword)) {
                $_SESSION['field_err'] = 'New username and password are required.';
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update students_form table
                if($this->userModel->updateUserCredentials($oldUsername, $newUsername, $hashedPassword)) {
                    $this->submissionModel->updateCredentials($id, $newUsername, $newPassword);
                    $_SESSION['success_msg'] = 'Student credentials updated successfully!';
                } else {
                    $_SESSION['field_err'] = 'Failed to update student credentials.';
                }
            }
        }
        header('Location: ' . URLROOT . '/students');
        exit;
    }

    public function send_credentials_mail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $submission = $this->submissionModel->getSubmissionById($id);
            if ($submission && !empty($submission->username) && !empty($submission->raw_password)) {
                $fields = $this->fieldModel->getFieldsWithDetails();
                $emailFieldId = null;
                foreach ($fields as $field) {
                    if (stripos($field->field_name, 'email') !== false) {
                        $emailFieldId = 'field_' . $field->id;
                        break;
                    }
                }
                $toEmail = null;
                if ($emailFieldId) {
                    $formData = json_decode($submission->form_data, true);
                    if (isset($formData[$emailFieldId])) {
                        $toEmail = $formData[$emailFieldId];
                    }
                }

                if ($toEmail && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                    if (MailHelper::sendCredentials($toEmail, $submission->username, $submission->raw_password, 'student')) {
                        $_SESSION['success_msg'] = 'Credentials email sent successfully!';
                    } else {
                        $_SESSION['field_err'] = 'Failed to send email via SMTP.';
                    }
                } else {
                    $_SESSION['field_err'] = 'Could not find a valid email address for this user.';
                }
            } else {
                $_SESSION['field_err'] = 'Submission or credentials not found.';
            }
        }
        header('Location: ' . URLROOT . '/students');
        exit;
    }

    public function reject_submission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            if($this->submissionModel->updateStatus($id, 'rejected')) {
                $_SESSION['success_msg'] = 'Submission rejected successfully.';
            } else {
                $_SESSION['field_err'] = 'Failed to reject submission.';
            }
        }
        header('Location: ' . URLROOT . '/students');
        exit;
    }
}
