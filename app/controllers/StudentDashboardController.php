<?php
class StudentDashboardController extends Controller {
    private $studentModel;
    private $fieldModel;

    public function __construct() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . URLROOT . '/student-portal');
            exit;
        }
        $this->studentModel = $this->model('StudentSubmission');
        $this->fieldModel = $this->model('FormField');
    }

    public function index() {
        $username = $_SESSION['username'];
        $submission = $this->studentModel->getSubmissionByUsername($username);
        $fields = $this->fieldModel->getFieldsWithDetails();

        $data = [
            'title' => 'My Profile',
            'username' => $username,
            'submission' => $submission,
            'fields' => $fields
        ];

        $this->view('student_portal/dashboard', $data);
    }
    public function tickets() {
        $userId = $_SESSION['user_id'];
        $ticketModel = $this->model('Ticket');
        $tickets = $ticketModel->getTicketsByUser($userId);

        $data = [
            'title' => 'My Tickets',
            'tickets' => $tickets
        ];

        $this->view('student_portal/tickets', $data);
    }

    public function raiseTicket() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $userId = $_SESSION['user_id'];

            if (!empty($subject) && !empty($message) && strlen($subject) <= 100 && strlen($message) <= 500) {
                $ticketModel = $this->model('Ticket');
                $ticketModel->createTicket($userId, $subject, $message);
            }
            header('Location: ' . URLROOT . '/student-dashboard/tickets');
            exit;
        }
    }

    public function viewTicket($id) {
        $ticketModel = $this->model('Ticket');
        $ticket = $ticketModel->getTicketById($id);

        if (!$ticket || $ticket->user_id !== $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/student-dashboard/tickets');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $replyMessage = trim($_POST['reply_message'] ?? '');
            if (!empty($replyMessage)) {
                $replies = json_decode($ticket->reply_json ?? '[]', true) ?? [];
                $replies[] = [
                    'userID' => $_SESSION['user_id'],
                    'message' => $replyMessage,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                $ticketModel->addReply($id, json_encode($replies));
                header('Location: ' . URLROOT . '/student-dashboard/viewTicket/' . $id);
                exit;
            }
        }

        $data = [
            'title' => 'View Ticket',
            'ticket' => $ticket
        ];

        $this->view('student_portal/view_ticket', $data);
    }

    public function myClasses() {
        $userId = $_SESSION['user_id'];
        $meetingModel = $this->model('LiveMeeting');
        $classes = $meetingModel->getMeetingsByStudent($userId);

        $onsiteClasses = [];
        $onlineClasses = [];

        foreach ($classes as $class) {
            if ($class->type === 'onsite') {
                $onsiteClasses[] = $class;
            } else {
                $onlineClasses[] = $class;
            }
        }

        $data = [
            'title' => 'My Classes',
            'onsite_classes' => $onsiteClasses,
            'online_classes' => $onlineClasses
        ];

        $this->view('student_portal/my_classes', $data);
    }
}
