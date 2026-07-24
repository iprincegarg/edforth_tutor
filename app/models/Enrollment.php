<?php
class Enrollment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function enrollStudent($userId, $courseId) {
        $this->db->query("INSERT INTO student_enrollments (user_id, course_id) VALUES (:user_id, :course_id)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':course_id', $courseId);
        
        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // 23000 is the SQLSTATE code for integrity constraint violation (e.g. unique key)
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function hasEnrolled($userId, $courseId) {
        $this->db->query("SELECT * FROM student_enrollments WHERE user_id = :user_id AND course_id = :course_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':course_id', $courseId);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }
}
