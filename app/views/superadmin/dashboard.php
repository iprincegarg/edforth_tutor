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

<div class="card" style="margin-top: 2rem; max-width: 100%;">
    <div class="card-header" style="text-align: left; margin-bottom: 1rem;">
        <h3 class="card-title" style="font-size: 1.25rem;">Recent Activity</h3>
    </div>
    <div style="padding: 1rem; color: var(--text-muted);">
        <p>No recent activity to show.</p>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
