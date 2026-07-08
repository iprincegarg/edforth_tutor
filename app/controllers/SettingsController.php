<?php
class SettingsController extends Controller {
    private $sectionModel;
    private $filterModel;
    private $fieldModel;
    private $submissionModel;
    private $userModel;
    private $settingModel;

    public function __construct() {
        if(!isLoggedIn()) {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
        $this->sectionModel = $this->model('Section');
        $this->filterModel = $this->model('Filter');
        $this->fieldModel = $this->model('FormField');
        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        // Redirect to a default settings page
        header('Location: ' . URLROOT . '/settings/front_cms');
        exit;
    }

    public function front_cms() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $content = $_POST['content'] ?? '';
            if($this->settingModel->updateSetting('front_page_content', $content)) {
                $_SESSION['success_msg'] = 'Front page content updated successfully!';
            } else {
                $_SESSION['error_msg'] = 'Something went wrong.';
            }
            header('Location: ' . URLROOT . '/settings/front_cms');
            exit;
        }

        $content = $this->settingModel->getSetting('front_page_content');
        
        $data = [
            'title' => 'Front CMS',
            'content' => $content,
            'success_msg' => $_SESSION['success_msg'] ?? '',
            'error_msg' => $_SESSION['error_msg'] ?? ''
        ];

        unset($_SESSION['success_msg']);
        unset($_SESSION['error_msg']);

        $this->view('settings/front_cms', $data);
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

        $fields = $this->fieldModel->getFieldsWithDetails();

        $data = [
            'title' => 'Tutor Details Form',
            'sections' => $sections,
            'sectionCount' => $sectionCount,
            'filters' => $filters,
            'filterCount' => $filterCount,
            'fields' => $fields,
            'section_name' => $_SESSION['section_name'] ?? '',
            'section_err' => $_SESSION['section_err'] ?? '',
            'filter_name' => $_SESSION['filter_name'] ?? '',
            'filter_values' => $_SESSION['filter_values'] ?? '',
            'filter_err' => $_SESSION['filter_err'] ?? '',
            'field_err' => $_SESSION['field_err'] ?? '',
            'field_form_data' => $_SESSION['field_form_data'] ?? [],
            'success_msg' => $_SESSION['success_msg'] ?? ''
        ];

        unset($_SESSION['section_name']);
        unset($_SESSION['section_err']);
        unset($_SESSION['filter_name']);
        unset($_SESSION['filter_values']);
        unset($_SESSION['filter_err']);
        unset($_SESSION['field_err']);
        unset($_SESSION['field_form_data']);
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
            } elseif($action === 'reorder_fields') {
                $order = $_POST['order'] ?? [];
                if (!empty($order) && is_array($order)) {
                    foreach ($order as $index => $id) {
                        $this->fieldModel->updateSequence((int)$id, (int)$index);
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
            } elseif($action === 'add_field' || $action === 'edit_field') {
                $fieldData = [
                    'id' => $_POST['field_id'] ?? 0,
                    'section_id' => $_POST['section_id'] ?? 0,
                    'field_name' => trim($_POST['field_name'] ?? ''),
                    'field_type' => $_POST['field_type'] ?? '',
                    'filter_id' => !empty($_POST['filter_id']) ? $_POST['filter_id'] : null,
                    'char_limit' => !empty($_POST['char_limit']) ? (int)$_POST['char_limit'] : null,
                    'placeholder_text' => trim($_POST['placeholder_text'] ?? ''),
                    'field_values' => trim($_POST['field_values'] ?? ''),
                    'is_required' => isset($_POST['is_required']) && $_POST['is_required'] === '1' ? 1 : 0,
                    'show_on_form' => isset($_POST['show_on_form']) && $_POST['show_on_form'] === '1' ? 1 : 0,
                    'show_to_user' => isset($_POST['show_to_user']) && $_POST['show_to_user'] === '1' ? 1 : 0,
                ];

                if(empty($fieldData['section_id'])) {
                    $data['field_err'] = 'Please select a section.';
                } elseif(empty($fieldData['field_name'])) {
                    $data['field_err'] = 'Please enter a field name.';
                } elseif(strlen($fieldData['field_name']) > 30) {
                    $data['field_err'] = 'Field name cannot exceed 30 characters.';
                } elseif(empty($fieldData['field_type'])) {
                    $data['field_err'] = 'Please select a field type.';
                }

                if(empty($data['field_err'])) {
                    if($fieldData['field_type'] === 'file') {
                        if($action === 'add_field' && $this->fieldModel->countFileFields() >= 5) {
                            $data['field_err'] = 'Maximum 5 file uploads allowed on the form.';
                        }
                    } elseif($fieldData['field_type'] === 'radio' || $fieldData['field_type'] === 'dropdown') {
                        $valuesArr = array_filter(array_map('trim', explode(',', $fieldData['field_values'])));
                        if (empty($valuesArr)) {
                            $data['field_err'] = 'Please provide field values (comma separated).';
                        } else {
                            foreach ($valuesArr as $val) {
                                if (strlen($val) > 50) {
                                    $data['field_err'] = 'Each value cannot exceed 50 characters.';
                                    break;
                                }
                            }
                            $fieldData['field_values'] = implode(', ', $valuesArr);
                        }
                    } elseif($fieldData['field_type'] === 'filter') {
                        if(empty($fieldData['filter_id'])) {
                            $data['field_err'] = 'Please select a filter.';
                        }
                    }
                }

                if(empty($data['field_err'])) {
                    if($action === 'add_field') {
                        if($this->fieldModel->addField($fieldData)) {
                            $_SESSION['success_msg'] = 'Field added successfully!';
                        } else {
                            $data['field_err'] = 'Something went wrong adding the field.';
                        }
                    } else {
                        if($this->fieldModel->updateField($fieldData)) {
                            $_SESSION['success_msg'] = 'Field updated successfully!';
                        } else {
                            $data['field_err'] = 'Something went wrong updating the field.';
                        }
                    }
                }

                if(!empty($data['field_err'])) {
                    $_SESSION['field_err'] = $data['field_err'];
                    $_SESSION['field_form_data'] = $fieldData;
                }
                
                header('Location: ' . URLROOT . '/settings/tutor_form#form');
                exit;
            } elseif($action === 'delete_field') {
                $id = $_POST['field_id'] ?? 0;
                if($this->fieldModel->deleteField($id)) {
                    $_SESSION['success_msg'] = 'Field deleted successfully!';
                } else {
                    $_SESSION['field_err'] = 'Could not delete field.';
                }
                header('Location: ' . URLROOT . '/settings/tutor_form#form');
                exit;
            }
        }

        $this->view('settings/tutor_form', $data);
    }
}
