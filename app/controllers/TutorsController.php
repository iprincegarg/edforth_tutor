<?php
class TutorsController extends Controller {
    private $submissionModel;
    private $userModel;
    private $fieldModel;

    private $filterModel;

    public function __construct() {
        if(!isLoggedIn()) {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
        $this->submissionModel = $this->model('TutorSubmission');
        $this->userModel = $this->model('User');
        $this->fieldModel = $this->model('FormField');
        $this->filterModel = $this->model('Filter');
        require_once APPROOT . '/helpers/MailHelper.php';
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $t_page = isset($_GET['t_page']) ? (int)$_GET['t_page'] : 1;
        $s_page = isset($_GET['s_page']) ? (int)$_GET['s_page'] : 1;
        if ($t_page < 1) $t_page = 1;
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
        $t_offset = ($t_page - 1) * $limit;
        $s_offset = ($s_page - 1) * $limit;

        $tutorSubmissions = $this->submissionModel->getPaginatedSubmissions('processed', $search, $limit, $t_offset, $activeFilters);
        $totalTutors = $this->submissionModel->getTotalSubmissions('processed', $search, $activeFilters);
        
        $pendingSubmissions = $this->submissionModel->getPaginatedSubmissions('pending', $search, $limit, $s_offset);
        $totalPending = $this->submissionModel->getTotalSubmissions('pending', $search);

        $fields = $this->fieldModel->getFieldsWithDetails();

        // Extract fields that have a filter assigned (for the filter dropdowns)
        $filterFields = array_filter($fields, function($f) {
            return !empty($f->filter_id) && !empty($f->filterValues);
        });
        
        $data = [
            'title' => 'Tutor Applications',
            'tutorSubmissions' => $tutorSubmissions,
            'totalTutors' => $totalTutors,
            't_page' => $t_page,
            't_totalPages' => ceil($totalTutors / $limit),
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

        $this->view('tutors/index', $data);
    }

    public function approve_submission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if(empty($username) || empty($password)) {
                $_SESSION['field_err'] = 'Username and password are required to approve the tutor.';
            } else {
                // Check if username already exists
                if ($this->userModel->getUserByUsername($username)) {
                    $_SESSION['field_err'] = 'User already exists.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    if($this->userModel->createUser($username, $hashedPassword, 'tutor', 1)) {
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
                            MailHelper::sendCredentials($toEmail, $username, $password, 'tutor');
                            $_SESSION['success_msg'] = 'Tutor approved, account created, and email sent successfully!';
                        } else {
                            $_SESSION['success_msg'] = 'Tutor approved and account created successfully! (Could not send email: no valid email field found)';
                        }
                    } else {
                        $_SESSION['field_err'] = 'Failed to create tutor account.';
                    }
                }
            }
        }
        header('Location: ' . URLROOT . '/tutors');
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
                
                // Update User table
                if($this->userModel->updateUserCredentials($oldUsername, $newUsername, $hashedPassword)) {
                    // Update tutors_form table
                    $this->submissionModel->updateCredentials($id, $newUsername, $newPassword);
                    $_SESSION['success_msg'] = 'Tutor credentials updated successfully!';
                } else {
                    $_SESSION['field_err'] = 'Failed to update tutor credentials.';
                }
            }
        }
        header('Location: ' . URLROOT . '/tutors');
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
                    if (MailHelper::sendCredentials($toEmail, $submission->username, $submission->raw_password, 'tutor')) {
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
        header('Location: ' . URLROOT . '/tutors');
        exit;
    }

    public function toggle_access() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $submission = $this->submissionModel->getSubmissionById($id);
            if ($submission && !empty($submission->username)) {
                if ($this->userModel->toggleStatus($submission->username)) {
                    $_SESSION['success_msg'] = 'Account access toggled successfully.';
                } else {
                    $_SESSION['field_err'] = 'Failed to toggle account access.';
                }
            } else {
                $_SESSION['field_err'] = 'Could not find user account.';
            }
        }
        header('Location: ' . URLROOT . '/tutors#Tutors');
        exit;
    }

    public function delete_account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['submission_id'] ?? 0;
            $submission = $this->submissionModel->getSubmissionById($id);
            if ($submission) {
                $deletedUser = true;
                if (!empty($submission->username)) {
                    $deletedUser = $this->userModel->deleteUser($submission->username);
                }
                
                if ($deletedUser && $this->submissionModel->deleteSubmission($id)) {
                    $_SESSION['success_msg'] = 'Account and submission permanently deleted.';
                } else {
                    $_SESSION['field_err'] = 'Failed to delete account or submission.';
                }
            } else {
                $_SESSION['field_err'] = 'Submission not found.';
            }
        }
        header('Location: ' . URLROOT . '/tutors#Tutors');
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
        header('Location: ' . URLROOT . '/tutors');
        exit;
    }
}
