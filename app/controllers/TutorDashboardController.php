<?php
class TutorDashboardController extends Controller {
    public function __construct() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'tutor') {
            header('Location: ' . URLROOT . '/tutor-portal');
            exit;
        }
    }

    public function index() {
        $data = [
            'title' => 'Tutor Dashboard',
            'username' => $_SESSION['username']
        ];

        $this->view('tutor_portal/dashboard', $data);
    }
}
