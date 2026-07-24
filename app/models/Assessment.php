<?php
class Assessment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addQuestion($data) {
        $this->db->query("INSERT INTO assessment_questions (course_id, question, option_a, option_b, option_c, option_d, correct_answer, explanation, marks) VALUES (:course_id, :question, :option_a, :option_b, :option_c, :option_d, :correct_answer, :explanation, :marks)");
        
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':option_a', $data['option_a']);
        $this->db->bind(':option_b', $data['option_b']);
        $this->db->bind(':option_c', $data['option_c']);
        $this->db->bind(':option_d', $data['option_d']);
        $this->db->bind(':correct_answer', $data['correct_answer']);
        $this->db->bind(':explanation', $data['explanation']);
        $this->db->bind(':marks', $data['marks']);
        
        return $this->db->execute();
    }

    public function getQuestionsPaginated($offset, $limit) {
        $this->db->query("SELECT * FROM assessment_questions ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalQuestionsCount() {
        $this->db->query("SELECT COUNT(*) as total FROM assessment_questions");
        $row = $this->db->single();
        return $row->total;
    }

    public function deleteQuestion($id) {
        $this->db->query("DELETE FROM assessment_questions WHERE id = :id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function updateQuestion($id, $data) {
        $this->db->query("UPDATE assessment_questions SET question = :question, option_a = :option_a, option_b = :option_b, option_c = :option_c, option_d = :option_d, correct_answer = :correct_answer, explanation = :explanation, marks = :marks WHERE id = :id");
        
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':option_a', $data['option_a']);
        $this->db->bind(':option_b', $data['option_b']);
        $this->db->bind(':option_c', $data['option_c']);
        $this->db->bind(':option_d', $data['option_d']);
        $this->db->bind(':correct_answer', $data['correct_answer']);
        $this->db->bind(':explanation', $data['explanation']);
        $this->db->bind(':marks', $data['marks']);
        
        return $this->db->execute();
    }
}
