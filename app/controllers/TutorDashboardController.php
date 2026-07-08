<?php
class TutorDashboardController extends Controller {
    private $tutorModel;
    private $fieldModel;

    public function __construct() {
        if(!isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'tutor') {
            header('Location: ' . URLROOT . '/tutor-portal');
            exit;
        }
        $this->tutorModel = $this->model('TutorSubmission');
        $this->fieldModel = $this->model('FormField');
    }

    public function index() {
        $username = $_SESSION['username'];
        $submission = $this->tutorModel->getSubmissionByUsername($username);
        $fields = $this->fieldModel->getFieldsWithDetails();

        $data = [
            'title' => 'My Profile',
            'username' => $username,
            'submission' => $submission,
            'fields' => $fields
        ];

        $this->view('tutor_portal/dashboard', $data);
    }
    public function tickets() {
        $userId = $_SESSION['user_id'];
        $ticketModel = $this->model('Ticket');
        $tickets = $ticketModel->getTicketsByUser($userId);

        $data = [
            'title' => 'My Tickets',
            'tickets' => $tickets
        ];

        $this->view('tutor_portal/tickets', $data);
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
            header('Location: ' . URLROOT . '/tutor-dashboard/tickets');
            exit;
        }
    }

    public function viewTicket($id) {
        $ticketModel = $this->model('Ticket');
        $ticket = $ticketModel->getTicketById($id);

        if (!$ticket || $ticket->user_id !== $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/tutor-dashboard/tickets');
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
                header('Location: ' . URLROOT . '/tutor-dashboard/viewTicket/' . $id);
                exit;
            }
        }

        $data = [
            'title' => 'View Ticket',
            'ticket' => $ticket
        ];

        $this->view('tutor_portal/view_ticket', $data);
    }

    public function myClasses() {
        $userId = $_SESSION['user_id'];
        $meetingModel = $this->model('LiveMeeting');
        $classes = $meetingModel->getMeetingsByTutor($userId);

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

        $this->view('tutor_portal/my_classes', $data);
    }
}
