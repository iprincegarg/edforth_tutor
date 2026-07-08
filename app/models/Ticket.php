<?php
class Ticket {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createTicket($userId, $subject, $message) {
        $this->db->query('INSERT INTO tickets (user_id, subject, message) VALUES (:user_id, :subject, :message)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':message', $message);
        
        return $this->db->execute();
    }

    public function getTicketsByUser($userId) {
        $this->db->query('SELECT * FROM tickets WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getAllTickets() {
        // Also fetch user details, assuming users table has username
        $this->db->query('SELECT t.*, u.username FROM tickets t LEFT JOIN user u ON t.user_id = u.id ORDER BY t.created_at DESC');
        return $this->db->resultSet();
    }

    public function getTicketById($id) {
        $this->db->query('SELECT t.*, u.username FROM tickets t LEFT JOIN user u ON t.user_id = u.id WHERE t.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addReply($ticketId, $replyJson) {
        $this->db->query('UPDATE tickets SET reply_json = :reply_json WHERE id = :id');
        $this->db->bind(':reply_json', $replyJson);
        $this->db->bind(':id', $ticketId);
        return $this->db->execute();
    }

    public function updateStatus($ticketId, $status) {
        $this->db->query('UPDATE tickets SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $ticketId);
        return $this->db->execute();
    }
}
?>
