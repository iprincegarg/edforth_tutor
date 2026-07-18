<?php
class Course {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCourses() {
        // Fetch all courses, order by level and then title for structured display
        $this->db->query("SELECT * FROM courses ORDER BY level ASC, title ASC");
        return $this->db->resultSet();
    }
    
    public function getCourseById($id) {
        $this->db->query("SELECT * FROM courses WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCourse($title, $level, $parentId) {
        $this->db->query("INSERT INTO courses (title, level, parent_id) VALUES (:title, :level, :parent_id)");
        $this->db->bind(':title', $title);
        $this->db->bind(':level', $level);
        
        if (empty($parentId) || $level == 1) {
            $this->db->bind(':parent_id', null);
        } else {
            $this->db->bind(':parent_id', $parentId);
        }
        
        return $this->db->execute();
    }

    public function updateCourse($id, $title, $level, $parentId) {
        $this->db->query("UPDATE courses SET title = :title, level = :level, parent_id = :parent_id WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $title);
        $this->db->bind(':level', $level);
        
        if (empty($parentId) || $level == 1) {
            $this->db->bind(':parent_id', null);
        } else {
            $this->db->bind(':parent_id', $parentId);
        }
        
        return $this->db->execute();
    }

    public function deleteCourse($id) {
        $this->db->query("DELETE FROM courses WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
