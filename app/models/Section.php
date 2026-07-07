<?php
class Section {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSections() {
        $this->db->query('SELECT * FROM tutor_sections ORDER BY createdAt ASC');
        return $this->db->resultSet();
    }

    public function getSectionCount() {
        $this->db->query('SELECT COUNT(*) as count FROM tutor_sections');
        $row = $this->db->single();
        return $row->count;
    }

    public function getSectionById($id) {
        $this->db->query('SELECT * FROM tutor_sections WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addSection($name) {
        $this->db->query('INSERT INTO tutor_sections (sectionName) VALUES (:name)');
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }

    public function updateSection($id, $name) {
        $this->db->query('UPDATE tutor_sections SET sectionName = :name WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }

    public function deleteSection($id) {
        // Only allow deletion if canDelete = 1
        $this->db->query('DELETE FROM tutor_sections WHERE id = :id AND canDelete = 1');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
