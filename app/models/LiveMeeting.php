<?php
class LiveMeeting {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createMeeting($tutorId, $topic, $roomName, $scheduledAt, $type) {
        $this->db->query('INSERT INTO live_meetings (tutor_id, topic, room_name, scheduled_at, type) VALUES (:tutor_id, :topic, :room_name, :scheduled_at, :type)');
        $this->db->bind(':tutor_id', $tutorId);
        $this->db->bind(':topic', $topic);
        $this->db->bind(':room_name', $roomName);
        $this->db->bind(':scheduled_at', $scheduledAt);
        $this->db->bind(':type', $type);
        
        return $this->db->execute();
    }

    public function getMeetingsByTutor($tutorId) {
        $this->db->query('SELECT * FROM live_meetings WHERE tutor_id = :tutor_id ORDER BY scheduled_at DESC');
        $this->db->bind(':tutor_id', $tutorId);
        return $this->db->resultSet();
    }

    public function getAllMeetings() {
        $this->db->query('SELECT m.*, u.username as tutor_name FROM live_meetings m LEFT JOIN user u ON m.tutor_id = u.id ORDER BY m.scheduled_at DESC');
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE live_meetings SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>
