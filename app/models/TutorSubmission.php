<?php
class TutorSubmission {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addSubmission($jsonData) {
        $this->db->query('INSERT INTO tutors_form (form_data) VALUES (:form_data)');
        $this->db->bind(':form_data', $jsonData);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllSubmissions() {
        $this->db->query('SELECT * FROM tutors_form ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getSubmissionById($id) {
        $this->db->query('SELECT * FROM tutors_form WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPaginatedSubmissions($status, $search = '', $limit = 25, $offset = 0, $activeFilters = []) {
        $sql = 'SELECT * FROM tutors_form WHERE ';
        if ($status === 'processed') {
            $sql .= 'status != "pending"';
        } else {
            $sql .= 'status = :status';
        }
        
        if (!empty($search)) {
            $sql .= ' AND (id = :search OR LOWER(form_data) LIKE LOWER(:search_like))';
        }

        // Apply filter conditions: each selected filter value must appear in form_data JSON
        foreach ($activeFilters as $fieldId => $value) {
            $sql .= ' AND LOWER(form_data) LIKE LOWER(:filter_val_' . $fieldId . ')';
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
        foreach ($activeFilters as $fieldId => $value) {
            $this->db->bind(':filter_val_' . $fieldId, '%' . $value . '%');
        }
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);

        return $this->db->resultSet();
    }

    public function getTotalSubmissions($status, $search = '', $activeFilters = []) {
        $sql = 'SELECT COUNT(*) as count FROM tutors_form WHERE ';
        if ($status === 'processed') {
            $sql .= 'status != "pending"';
        } else {
            $sql .= 'status = :status';
        }
        
        if (!empty($search)) {
            $sql .= ' AND (id = :search OR LOWER(form_data) LIKE LOWER(:search_like))';
        }

        foreach ($activeFilters as $fieldId => $value) {
            $sql .= ' AND LOWER(form_data) LIKE LOWER(:filter_val_' . $fieldId . ')';
        }

        $this->db->query($sql);
        if ($status !== 'processed') {
            $this->db->bind(':status', $status);
        }
        if (!empty($search)) {
            $this->db->bind(':search', $search);
            $this->db->bind(':search_like', '%' . $search . '%');
        }
        foreach ($activeFilters as $fieldId => $value) {
            $this->db->bind(':filter_val_' . $fieldId, '%' . $value . '%');
        }

        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE tutors_form SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateStatusAndCredentials($id, $status, $username, $password) {
        $this->db->query('UPDATE tutors_form SET status = :status, username = :username, raw_password = :password WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateCredentials($id, $username, $password) {
        $this->db->query('UPDATE tutors_form SET username = :username, raw_password = :password WHERE id = :id');
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSubmissionByUsername($username) {
        $this->db->query('SELECT * FROM tutors_form WHERE username = :username LIMIT 1');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }
}
