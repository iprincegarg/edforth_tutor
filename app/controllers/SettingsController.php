<?php
class SettingsController extends Controller {
    private $sectionModel;

    public function __construct() {
        if(!isLoggedIn()) {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
        $this->sectionModel = $this->model('Section');
        $this->filterModel = $this->model('Filter');
    }

    public function index() {
        // Redirect to a default settings page
        header('Location: ' . URLROOT . '/settings/tutor_form');
        exit;
    }

    public function tutor_form() {
        // Use role permission on the page
        if(!hasPermission('tutor_form')) {
            // Because sa has all permissions this will pass
            // In the future this can be expanded
        }

        $sections = $this->sectionModel->getSections();
        $sectionCount = $this->sectionModel->getSectionCount();

        $filters = $this->filterModel->getFilters();
        $filterCount = $this->filterModel->getFilterCount();

        $data = [
            'title' => 'Tutor Details Form',
            'sections' => $sections,
            'sectionCount' => $sectionCount,
            'filters' => $filters,
            'filterCount' => $filterCount,
            'section_name' => $_SESSION['section_name'] ?? '',
            'section_err' => $_SESSION['section_err'] ?? '',
            'filter_name' => $_SESSION['filter_name'] ?? '',
            'filter_values' => $_SESSION['filter_values'] ?? '',
            'filter_err' => $_SESSION['filter_err'] ?? '',
            'success_msg' => $_SESSION['success_msg'] ?? ''
        ];

        unset($_SESSION['section_name']);
        unset($_SESSION['section_err']);
        unset($_SESSION['filter_name']);
        unset($_SESSION['filter_values']);
        unset($_SESSION['filter_err']);
        unset($_SESSION['success_msg']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            if($action === 'add_section') {
                $data['section_name'] = trim($_POST['section_name'] ?? '');

                if(empty($data['section_name'])) {
                    $data['section_err'] = 'Please enter a section name.';
                } elseif(strlen($data['section_name']) > 30) {
                    $data['section_err'] = 'Section name cannot exceed 30 characters.';
                } elseif($sectionCount >= 10) {
                    $data['section_err'] = 'Maximum limit of 10 sections reached.';
                }

                if(empty($data['section_err'])) {
                    if($this->sectionModel->addSection($data['section_name'])) {
                        $_SESSION['success_msg'] = 'Section added successfully!';
                    } else {
                        $_SESSION['section_err'] = 'Something went wrong.';
                        $_SESSION['section_name'] = $data['section_name'];
                    }
                } else {
                    $_SESSION['section_err'] = $data['section_err'];
                    $_SESSION['section_name'] = $data['section_name'];
                }
                header('Location: ' . URLROOT . '/settings/tutor_form');
                exit;
            } elseif($action === 'edit_section') {
                $id = $_POST['section_id'] ?? 0;
                $name = trim($_POST['section_name'] ?? '');

                if(empty($name)) {
                    $data['section_err'] = 'Section name cannot be empty.';
                } elseif(strlen($name) > 30) {
                    $data['section_err'] = 'Section name cannot exceed 30 characters.';
                }

                if(empty($data['section_err'])) {
                    if($this->sectionModel->updateSection($id, $name)) {
                        $_SESSION['success_msg'] = 'Section updated successfully!';
                    } else {
                        $_SESSION['section_err'] = 'Something went wrong.';
                    }
                } else {
                    $_SESSION['section_err'] = $data['section_err'];
                }
                header('Location: ' . URLROOT . '/settings/tutor_form');
                exit;
            } elseif($action === 'delete_section') {
                $id = $_POST['section_id'] ?? 0;
                if($this->sectionModel->deleteSection($id)) {
                    $_SESSION['success_msg'] = 'Section deleted successfully!';
                } else {
                    $_SESSION['section_err'] = 'Could not delete section (it may be protected).';
                }
                header('Location: ' . URLROOT . '/settings/tutor_form');
                exit;
            } elseif($action === 'reorder_sections') {
                $order = $_POST['order'] ?? [];
                if (!empty($order) && is_array($order)) {
                    foreach ($order as $index => $id) {
                        $this->sectionModel->updateSectionOrder((int)$id, (int)$index);
                    }
                    echo json_encode(['status' => 'success']);
                    exit;
                }
            } elseif($action === 'add_filter') {
                $data['filter_name'] = trim($_POST['filter_name'] ?? '');
                $data['filter_values'] = trim($_POST['filter_values'] ?? '');

                if(empty($data['filter_name'])) {
                    $data['filter_err'] = 'Please enter a filter name.';
                } elseif(strlen($data['filter_name']) > 25) {
                    $data['filter_err'] = 'Filter name cannot exceed 25 characters.';
                }

                if(empty($data['filter_err']) && empty($data['filter_values'])) {
                    $data['filter_err'] = 'Please enter filter values.';
                }

                // Validate values
                if(empty($data['filter_err'])) {
                    $valuesArr = array_filter(array_map('trim', explode(',', $data['filter_values'])));
                    if (count($valuesArr) > 50) {
                        $data['filter_err'] = 'Maximum 50 values allowed.';
                    } else {
                        foreach ($valuesArr as $val) {
                            if (strlen($val) > 45) {
                                $data['filter_err'] = 'Each value cannot exceed 45 characters. (Check: "' . htmlspecialchars($val) . '")';
                                break;
                            }
                        }
                    }
                    $data['filter_values'] = implode(', ', $valuesArr);
                }

                if(empty($data['filter_err'])) {
                    if($this->filterModel->addFilter($data['filter_name'], $data['filter_values'])) {
                        $_SESSION['success_msg'] = 'Filter added successfully!';
                    } else {
                        $_SESSION['filter_err'] = 'Something went wrong.';
                        $_SESSION['filter_name'] = $data['filter_name'];
                        $_SESSION['filter_values'] = $data['filter_values'];
                    }
                } else {
                    $_SESSION['filter_err'] = $data['filter_err'];
                    $_SESSION['filter_name'] = $data['filter_name'];
                    $_SESSION['filter_values'] = $data['filter_values'];
                }
                header('Location: ' . URLROOT . '/settings/tutor_form#filters');
                exit;
            } elseif($action === 'edit_filter') {
                $id = $_POST['filter_id'] ?? 0;
                $name = trim($_POST['filter_name'] ?? '');
                $values = trim($_POST['filter_values'] ?? '');

                if(empty($name)) {
                    $data['filter_err'] = 'Filter name cannot be empty.';
                } elseif(strlen($name) > 25) {
                    $data['filter_err'] = 'Filter name cannot exceed 25 characters.';
                }

                if(empty($data['filter_err']) && empty($values)) {
                    $data['filter_err'] = 'Please enter filter values.';
                }

                if(empty($data['filter_err'])) {
                    $valuesArr = array_filter(array_map('trim', explode(',', $values)));
                    if (count($valuesArr) > 50) {
                        $data['filter_err'] = 'Maximum 50 values allowed.';
                    } else {
                        foreach ($valuesArr as $val) {
                            if (strlen($val) > 45) {
                                $data['filter_err'] = 'Each value cannot exceed 45 characters.';
                                break;
                            }
                        }
                    }
                    $values = implode(', ', $valuesArr);
                }

                if(empty($data['filter_err'])) {
                    if($this->filterModel->updateFilter($id, $name, $values)) {
                        $_SESSION['success_msg'] = 'Filter updated successfully!';
                    } else {
                        $_SESSION['filter_err'] = 'Something went wrong.';
                    }
                } else {
                    $_SESSION['filter_err'] = $data['filter_err'];
                }
                header('Location: ' . URLROOT . '/settings/tutor_form#filters');
                exit;
            } elseif($action === 'delete_filter') {
                $id = $_POST['filter_id'] ?? 0;
                if($this->filterModel->deleteFilter($id)) {
                    $_SESSION['success_msg'] = 'Filter deleted successfully!';
                } else {
                    $_SESSION['filter_err'] = 'Could not delete filter.';
                }
                header('Location: ' . URLROOT . '/settings/tutor_form#filters');
                exit;
            }
        }

        $this->view('settings/tutor_form', $data);
    }
}
