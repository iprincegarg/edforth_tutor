<?php
class FormField {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getFieldsWithDetails() {
        $this->db->query('
            SELECT f.*, s.sectionName, fi.filterName 
            FROM tutor_form_fields f
            JOIN tutor_sections s ON f.section_id = s.id
            LEFT JOIN tutor_filters fi ON f.filter_id = fi.id
            ORDER BY s.sequence_id ASC, f.sequence_id ASC, f.createdAt ASC
        ');
        return $this->db->resultSet();
    }

    public function getFieldById($id) {
        $this->db->query('SELECT * FROM tutor_form_fields WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function countFileFields() {
        $this->db->query('SELECT COUNT(*) as count FROM tutor_form_fields WHERE field_type = "file"');
        $row = $this->db->single();
        return $row->count;
    }

    public function addField($data) {
        $this->db->query('
            INSERT INTO tutor_form_fields 
            (section_id, field_name, field_type, filter_id, char_limit, placeholder_text, field_values, is_required, show_on_form, show_to_user) 
            VALUES 
            (:section_id, :field_name, :field_type, :filter_id, :char_limit, :placeholder_text, :field_values, :is_required, :show_on_form, :show_to_user)
        ');
        $this->db->bind(':section_id', $data['section_id']);
        $this->db->bind(':field_name', $data['field_name']);
        $this->db->bind(':field_type', $data['field_type']);
        $this->db->bind(':filter_id', !empty($data['filter_id']) ? $data['filter_id'] : null);
        $this->db->bind(':char_limit', !empty($data['char_limit']) ? $data['char_limit'] : null);
        $this->db->bind(':placeholder_text', !empty($data['placeholder_text']) ? $data['placeholder_text'] : null);
        $this->db->bind(':field_values', !empty($data['field_values']) ? $data['field_values'] : null);
        $this->db->bind(':is_required', $data['is_required']);
        $this->db->bind(':show_on_form', $data['show_on_form']);
        $this->db->bind(':show_to_user', $data['show_to_user']);
        
        return $this->db->execute();
    }

    public function updateField($data) {
        $this->db->query('
            UPDATE tutor_form_fields 
            SET section_id = :section_id, 
                field_name = :field_name, 
                field_type = :field_type, 
                filter_id = :filter_id, 
                char_limit = :char_limit, 
                placeholder_text = :placeholder_text, 
                field_values = :field_values, 
                is_required = :is_required, 
                show_on_form = :show_on_form, 
                show_to_user = :show_to_user
            WHERE id = :id
        ');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':section_id', $data['section_id']);
        $this->db->bind(':field_name', $data['field_name']);
        $this->db->bind(':field_type', $data['field_type']);
        $this->db->bind(':filter_id', !empty($data['filter_id']) ? $data['filter_id'] : null);
        $this->db->bind(':char_limit', !empty($data['char_limit']) ? $data['char_limit'] : null);
        $this->db->bind(':placeholder_text', !empty($data['placeholder_text']) ? $data['placeholder_text'] : null);
        $this->db->bind(':field_values', !empty($data['field_values']) ? $data['field_values'] : null);
        $this->db->bind(':is_required', $data['is_required']);
        $this->db->bind(':show_on_form', $data['show_on_form']);
        $this->db->bind(':show_to_user', $data['show_to_user']);
        
        return $this->db->execute();
    }

    public function deleteField($id) {
        $this->db->query('DELETE FROM tutor_form_fields WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateSequence($id, $sequence) {
        $this->db->query('UPDATE tutor_form_fields SET sequence_id = :sequence WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':sequence', $sequence);
        return $this->db->execute();
    }
}
