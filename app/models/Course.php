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

    public function getCoursesByParent($parentId = null) {
        if ($parentId === null) {
            $this->db->query("SELECT * FROM courses WHERE parent_id IS NULL ORDER BY title ASC");
        } else {
            $this->db->query("SELECT * FROM courses WHERE parent_id = :parent_id ORDER BY title ASC");
            $this->db->bind(':parent_id', $parentId);
        }
        return $this->db->resultSet();
    }

    public function hasChildren($courseId) {
        $this->db->query("SELECT COUNT(*) as count FROM courses WHERE parent_id = :course_id");
        $this->db->bind(':course_id', $courseId);
        $row = $this->db->single();
        return $row->count > 0;
    }
}
