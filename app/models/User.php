<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new user
    public function createUser($username, $hashedPassword, $role, $status) {
        $this->db->query('INSERT INTO user (role, username, pass, status) VALUES (:role, :username, :pass, :status)');
        $this->db->bind(':role', $role);
        $this->db->bind(':username', $username);
        $this->db->bind(':pass', $hashedPassword);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
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

    // Token generation
    public function createSessionToken($userId, $ipAddress, $userAgent) {
        $token = bin2hex(random_bytes(32));
        $this->db->query('INSERT INTO user_authentication (user_id, token, ip_address, user_agent) VALUES (:user_id, :token, :ip_address, :user_agent)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token);
        $this->db->bind(':ip_address', $ipAddress);
        $this->db->bind(':user_agent', $userAgent);
        $this->db->execute();
        return $token;
    }

    // Token validation and timeout logic
    public function validateAndUpdateToken($token) {
        $this->db->query('SELECT ua.*, u.status as user_status FROM user_authentication ua JOIN user u ON ua.user_id = u.id WHERE ua.token = :token AND ua.is_active = 1');
        $this->db->bind(':token', $token);
        $auth = $this->db->single();

        if ($auth) {
            // Check if the user is active (status = 1)
            if ($auth->user_status != 1) {
                $this->invalidateToken($token);
                return false;
            }

            $lastActivity = strtotime($auth->last_activity);
            $currentTime = time();
            $diffHours = ($currentTime - $lastActivity) / 3600;

            if ($diffHours > 3) {
                // Expired / Idle timeout
                $this->invalidateToken($token);
                return false;
            } else {
                // Valid, update last activity
                $this->db->query('UPDATE user_authentication SET last_activity = CURRENT_TIMESTAMP WHERE token = :token');
                $this->db->bind(':token', $token);
                $this->db->execute();
                return true;
            }
        }
        return false;
    }

    // Invalidate token manually (logout)
    public function invalidateToken($token) {
        $this->db->query('UPDATE user_authentication SET is_active = 0 WHERE token = :token');
        $this->db->bind(':token', $token);
        $this->db->execute();
    }
    public function updateUserCredentials($oldUsername, $newUsername, $newHashedPassword) {
        $this->db->query('UPDATE user SET username = :newUsername, pass = :newPass WHERE username = :oldUsername');
        $this->db->bind(':newUsername', $newUsername);
        $this->db->bind(':newPass', $newHashedPassword);
        $this->db->bind(':oldUsername', $oldUsername);
        return $this->db->execute();
    }
}
