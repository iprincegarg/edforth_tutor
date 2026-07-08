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

    public function tickets() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sa') {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }

        $ticketModel = $this->model('Ticket');
        $tickets = $ticketModel->getAllTickets();

        $data = [
            'title' => 'Support Tickets',
            'tickets' => $tickets
        ];

        $this->view('superadmin/tickets', $data);
    }

    public function viewTicket($id) {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sa') {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }

        $ticketModel = $this->model('Ticket');
        $ticket = $ticketModel->getTicketById($id);

        if (!$ticket) {
            header('Location: ' . URLROOT . '/superadmin/tickets');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['close_ticket'])) {
                $ticketModel->updateStatus($id, 'closed');
            } else {
                $replyMessage = trim($_POST['reply_message'] ?? '');
                if (!empty($replyMessage)) {
                    $replies = json_decode($ticket->reply_json ?? '[]', true) ?? [];
                    $replies[] = [
                        'userID' => $_SESSION['user_id'], // admin user id
                        'message' => $replyMessage,
                        'timestamp' => date('Y-m-d H:i:s')
                    ];
                    $ticketModel->addReply($id, json_encode($replies));
                }
            }
            header('Location: ' . URLROOT . '/superadmin/viewTicket/' . $id);
            exit;
        }

        $data = [
            'title' => 'View Ticket',
            'ticket' => $ticket
        ];

        $this->view('superadmin/view_ticket', $data);
    }

    public function classes() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sa') {
            header('Location: ' . URLROOT . '/superadmin/login');
            exit;
        }

        $meetingModel = $this->model('LiveMeeting');
        $userModel = $this->model('User');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tutorId = $_POST['tutor_id'] ?? '';
            $topic = trim($_POST['topic'] ?? '');
            $scheduledAt = $_POST['scheduled_at'] ?? '';
            $type = $_POST['type'] ?? 'online';

            if (!empty($tutorId) && !empty($topic) && !empty($scheduledAt)) {
                $roomName = null;
                if ($type === 'online') {
                    $roomName = 'EdforthClass_' . uniqid();
                }
                $meetingModel->createMeeting($tutorId, $topic, $roomName, $scheduledAt, $type);
            }
            header('Location: ' . URLROOT . '/superadmin/classes');
            exit;
        }

        $meetings = $meetingModel->getAllMeetings();
        $tutors = $userModel->getActiveTutors();

        $data = [
            'title' => 'Classes',
            'meetings' => $meetings,
            'tutors' => $tutors
        ];

        $this->view('superadmin/live_meetings', $data);
    }

    public function createUserSession($user) {
        // Log the login
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $browserAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $this->userModel->logLogin($user->id, $browserAgent, $ipAddress);

        // Generate and store secure token
        $token = $this->userModel->createSessionToken($user->id, $ipAddress, $browserAgent);
        
        // Destroy old session IDs for security
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        $_SESSION['auth_token'] = $token;

        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }

    public function logout() {
        // Invalidate token in DB
        if(isset($_SESSION['auth_token'])) {
            $this->userModel->invalidateToken($_SESSION['auth_token']);
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['auth_token']);
        session_destroy();

        header('Location: ' . URLROOT . '/superadmin/login');
        exit;
    }
}
