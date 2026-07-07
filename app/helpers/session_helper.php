<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['auth_token'])) {
        return false;
    }

    // We need to validate the token against the database.
    require_once dirname(__DIR__) . '/core/Database.php';
    require_once dirname(__DIR__) . '/models/User.php';
    
    $userModel = new User();
    $isValid = $userModel->validateAndUpdateToken($_SESSION['auth_token']);
    
    if(!$isValid) {
        // Idle timeout or invalid token
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['auth_token']);
        session_destroy();
        return false;
    }

    return true;
}

// Check if the current user has a specific permission
function hasPermission($permission) {
    if(!isLoggedIn()) {
        return false;
    }
    
    // For now, superadmin has all permissions.
    // Future: Check against a permissions table for other roles.
    if(isset($_SESSION['role']) && $_SESSION['role'] === 'sa') {
        return true;
    }
    
    return false;
}
