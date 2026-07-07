<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="stat-card-grid">
    <div class="stat-card">
        <div class="stat-card-title">Total Users</div>
        <div class="stat-card-value"><?php echo $data['stats']['total_users']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Active Tutors</div>
        <div class="stat-card-value"><?php echo $data['stats']['active_tutors']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">Total Revenue</div>
        <div class="stat-card-value"><?php echo $data['stats']['total_revenue']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-title">System Health</div>
        <div class="stat-card-value"><?php echo $data['stats']['system_health']; ?></div>
    </div>
</div>

<div class="card dashboard-recent-activity-card">
    <div class="card-header dashboard-card-header-left">
        <h3 class="card-title dashboard-card-title-lg">Recent Activity</h3>
    </div>
    <div class="dashboard-recent-activity-content">
        <p>No recent activity to show.</p>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
