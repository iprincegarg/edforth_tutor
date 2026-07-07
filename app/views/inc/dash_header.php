<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> | Dashboard</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/common.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dashboard.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <?php require_once APPROOT . '/views/components/sidebar.php'; ?>
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
                        ☰
                    </button>
                    <h2><?php echo $data['title'] ?? 'Dashboard'; ?></h2>
                </div>
                <div class="header-right">
                    <span class="user-greeting">Hello, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                </div>
            </header>
            <div class="dashboard-content">
