<?php
class DashboardController extends Controller {
    public function __construct() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sa') {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }
    }

    public function index() {
        $data = [
            'title' => 'Dashboard Overview',
            'username' => $_SESSION['username'],
            'stats' => [
                'total_users' => 1250,
                'active_tutors' => 45,
                'total_revenue' => '$12,450',
                'system_health' => '99.9%'
            ]
        ];

        $this->view('superadmin/dashboard', $data);
    }
}
