<?php
class FrontController extends Controller {
    private $settingModel;

    public function __construct() {
        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        $content = $this->settingModel->getSetting('front_page_content');
        
        $data = [
            'content' => $content
        ];

        $this->view('front/index', $data);
    }
}
