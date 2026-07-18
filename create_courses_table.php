<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

try {
    $db = new Database();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(30) NOT NULL,
        level INT NOT NULL,
        parent_id INT NULL,
        FOREIGN KEY (parent_id) REFERENCES courses(id) ON DELETE CASCADE
    );
    ";
    
    $db->query($sql);
    $db->execute();
    
    echo "Table 'courses' created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
