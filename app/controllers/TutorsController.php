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
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                if($this->userModel->createUser($username, $hashedPassword, 'tutor', 1)) {
                    $this->submissionModel->updateStatus($id, 'approved');
                    $_SESSION['success_msg'] = 'Tutor approved and account created successfully!';
                } else {
                    $_SESSION['field_err'] = 'Failed to create tutor account.';
                }
            }
        }
        header('Location: ' . URLROOT . '/tutors');
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
