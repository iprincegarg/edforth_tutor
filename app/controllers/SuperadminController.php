<?php
class SuperadminController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        if(isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'sa') {
            header('Location: ' . URLROOT . '/dashboard');
            exit;
        }
        header('Location: ' . URLROOT . '/superadmin/login');
        exit;
    }

    public function login() {
        if(isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'sa') {
            header('Location: ' . URLROOT . '/dashboard');
            exit;
        }

        $data = [
            'role' => 'Superadmin',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['email'] = trim($_POST['email'] ?? '');
            $data['password'] = trim($_POST['password'] ?? '');

            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter your username.';
            }

            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter your password.';
            }

            if(empty($data['email_err']) && empty($data['password_err'])) {
                $loggedInUser = $this->userModel->login($data['email'], $data['password'], 'sa');

                if($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Invalid credentials or inactive account.';
                    $this->view('superadmin/login', $data);
                }
            } else {
                $this->view('superadmin/login', $data);
            }
        } else {
            $this->view('superadmin/login', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        // Log the login
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $browserAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $this->userModel->logLogin($user->id, $browserAgent, $ipAddress);

        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy();

        header('Location: ' . URLROOT . '/superadmin/login');
        exit;
    }
}
