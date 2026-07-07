<?php
class SettingsController extends Controller {
    private $sectionModel;

    public function __construct() {
        if(!isLoggedIn()) {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
        $this->sectionModel = $this->model('Section');
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

        $data = [
            'title' => 'Tutor Details Form',
            'sections' => $sections,
            'sectionCount' => $sectionCount,
            'section_name' => '',
            'section_err' => '',
            'success_msg' => ''
        ];

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
                        $data['success_msg'] = 'Section added successfully!';
                        $data['section_name'] = '';
                        $data['sections'] = $this->sectionModel->getSections();
                        $data['sectionCount'] = $this->sectionModel->getSectionCount();
                    } else {
                        $data['section_err'] = 'Something went wrong.';
                    }
                }
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
                        $data['success_msg'] = 'Section updated successfully!';
                        $data['sections'] = $this->sectionModel->getSections();
                    }
                }
            } elseif($action === 'delete_section') {
                $id = $_POST['section_id'] ?? 0;
                if($this->sectionModel->deleteSection($id)) {
                    $data['success_msg'] = 'Section deleted successfully!';
                    $data['sections'] = $this->sectionModel->getSections();
                    $data['sectionCount'] = $this->sectionModel->getSectionCount();
                } else {
                    $data['section_err'] = 'Could not delete section (it may be protected).';
                }
            }
        }

        $this->view('settings/tutor_form', $data);
    }
}
