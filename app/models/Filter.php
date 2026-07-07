<?php
class Filter {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getFilters() {
        $this->db->query('SELECT * FROM tutor_filters ORDER BY createdAt ASC');
        return $this->db->resultSet();
    }

    public function getFilterCount() {
        $this->db->query('SELECT COUNT(*) as count FROM tutor_filters');
        $row = $this->db->single();
        return $row->count;
    }

    public function getFilterById($id) {
        $this->db->query('SELECT * FROM tutor_filters WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addFilter($name, $values) {
        $this->db->query('INSERT INTO tutor_filters (filterName, filterValues) VALUES (:name, :values)');
        $this->db->bind(':name', $name);
        $this->db->bind(':values', $values);
        return $this->db->execute();
    }

    public function updateFilter($id, $name, $values) {
        $this->db->query('UPDATE tutor_filters SET filterName = :name, filterValues = :values WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        $this->db->bind(':values', $values);
        return $this->db->execute();
    }

    public function deleteFilter($id) {
        $this->db->query('DELETE FROM tutor_filters WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
