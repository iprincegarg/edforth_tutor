<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Login user
    public function login($email, $password, $role) {
        // Here we use username for email input as per the request spec where username=admin
        $this->db->query('SELECT * FROM user WHERE username = :username AND role = :role AND status = 1');
        $this->db->bind(':username', $email);
        $this->db->bind(':role', $role);

        $row = $this->db->single();

        if($row) {
            $hashed_password = $row->pass;
            if(password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Log the user login
    public function logLogin($userId, $browserAgent, $ipAddress) {
        $this->db->query('INSERT INTO logs (userid, browser_agent, ipaddress) VALUES (:userid, :browser_agent, :ipaddress)');
        $this->db->bind(':userid', $userId);
        $this->db->bind(':browser_agent', $browserAgent);
        $this->db->bind(':ipaddress', $ipAddress);
        $this->db->execute();
    }
}
