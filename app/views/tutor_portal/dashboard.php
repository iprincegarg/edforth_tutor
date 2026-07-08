<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="margin: 20px;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px;">
                <h3 class="card-title" style="margin: 0;">Welcome, <?php echo htmlspecialchars($data['username']); ?>!</h3>
            </div>
            <div class="card-body" style="padding: 20px;">
                <p>Welcome to your Tutor Dashboard. Here you will be able to manage your profile, view your assigned students, and track your schedule.</p>
                
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> More features for the Tutor Dashboard will be added soon!
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
