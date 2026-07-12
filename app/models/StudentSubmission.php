<?php
class StudentSubmission {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addSubmission($jsonData) {
        $this->db->query('INSERT INTO students_form (form_data) VALUES (:form_data)');
        $this->db->bind(':form_data', $jsonData);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllSubmissions() {
        $this->db->query('SELECT * FROM students_form ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getPaginatedSubmissions($status, $search = '', $limit = 25, $offset = 0) {
        $sql = 'SELECT * FROM students_form WHERE ';
        if ($status === 'processed') {
            $sql .= 'status != "pending"';
        } else {
            $sql .= 'status = :status';
        }
        
        if (!empty($search)) {
            $sql .= ' AND (id = :search OR LOWER(form_data) LIKE LOWER(:search_like))';
        }

        $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';

        $this->db->query($sql);
        if ($status !== 'processed') {
            $this->db->bind(':status', $status);
        }
        if (!empty($search)) {
            $this->db->bind(':search', $search);
            $this->db->bind(':search_like', '%' . $search . '%');
        }
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);

        return $this->db->resultSet();
    }

    public function getTotalSubmissions($status, $search = '') {
        $sql = 'SELECT COUNT(*) as count FROM students_form WHERE ';
        if ($status === 'processed') {
            $sql .= 'status != "pending"';
        } else {
            $sql .= 'status = :status';
        }
        
        if (!empty($search)) {
            $sql .= ' AND (id = :search OR LOWER(form_data) LIKE LOWER(:search_like))';
        }

        $this->db->query($sql);
        if ($status !== 'processed') {
            $this->db->bind(':status', $status);
        }
        if (!empty($search)) {
            $this->db->bind(':search', $search);
            $this->db->bind(':search_like', '%' . $search . '%');
        }

        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE students_form SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateStatusAndCredentials($id, $status, $username, $password) {
        $this->db->query('UPDATE students_form SET status = :status, username = :username, raw_password = :password WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateCredentials($id, $username, $password) {
        $this->db->query('UPDATE students_form SET username = :username, raw_password = :password WHERE id = :id');
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSubmissionByUsername($username) {
        $this->db->query('SELECT * FROM students_form WHERE username = :username LIMIT 1');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }
}
