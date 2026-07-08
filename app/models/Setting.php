<?php
class Setting {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getSetting($key) {
        $this->db->query("SELECT setting_value FROM settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $row = $this->db->single();

        return $row ? $row->setting_value : null;
    }

    public function updateSetting($key, $value) {
        // Check if exists
        $this->db->query("SELECT id FROM settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $row = $this->db->single();

        if ($row) {
            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
        } else {
            $this->db->query("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        }
        
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);

        return $this->db->execute();
    }
}
