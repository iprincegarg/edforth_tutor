<?php
class FindTutorController extends Controller {
    private $submissionModel;
    private $fieldModel;
    private $filterModel;

    public function __construct() {
        $this->submissionModel = $this->model('TutorSubmission');
        $this->fieldModel = $this->model('FormField');
        $this->filterModel = $this->model('Filter');
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        // Collect active filter selections
        $activeFilters = [];
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'filter_field_') === 0 && !empty($value)) {
                $fieldId = str_replace('filter_field_', '', $key);
                $activeFilters[(int)$fieldId] = $value;
            }
        }

        $limit = 12; // 12 tutors per page
        $offset = ($page - 1) * $limit;

        // Fetch approved tutors
        $tutors = $this->submissionModel->getPaginatedSubmissions('approved', $search, $limit, $offset, $activeFilters);
        $totalTutors = $this->submissionModel->getTotalSubmissions('approved', $search, $activeFilters);
        $totalPages = ceil($totalTutors / $limit);

        $fields = $this->fieldModel->getFieldsWithDetails();
        
        // Extract fields that should be shown to the user
        $publicFields = array_filter($fields, function($f) {
            return $f->show_to_user == 1;
        });

        // Extract fields that act as filters
        $filterFields = array_filter($fields, function($f) {
            return !empty($f->filter_id) && !empty($f->filterValues);
        });

        $data = [
            'tutors' => $tutors,
            'totalTutors' => $totalTutors,
            'page' => $page,
            'totalPages' => $totalPages,
            'publicFields' => $publicFields,
            'filterFields' => $filterFields,
            'search' => $search,
            'activeFilters' => $activeFilters
        ];

        $this->view('front/find_tutor', $data);
    }
}
?>
