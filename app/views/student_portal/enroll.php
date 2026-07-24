<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Toast Notifications -->
<div id="toast-container" class="toast-container">
    <?php if (!empty($data['success_msg'])): ?>
        <div class="toast-alert toast-success">
            <span class="toast-message-bold">Success:</span><?php echo $data['success_msg']; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($data['error_msg'])): ?>
        <div class="toast-alert toast-error">
            <span class="toast-message-bold">Error:</span><?php echo $data['error_msg']; ?>
        </div>
    <?php endif; ?>
</div>

<div class="tab-content tutor-form-content">
    <div class="tab-pane active">
        <div class="tutor-form-container" style="display: block; width: 100%;">
            <div class="card tutor-card" style="width: 100%;">
                <div class="card-header tutor-card-header" style="display: flex; justify-content: flex-start; align-items: center; gap: 15px; min-height: 40px;">
                    <?php if ($data['parentCourse']): ?>
                        <a href="<?php echo URLROOT; ?>/student-dashboard/enroll<?php echo $data['parentCourse']->parent_id ? '?parent_id='.$data['parentCourse']->parent_id : ''; ?>" class="btn btn-sm btn-outline-primary" style="width: auto;">&laquo; Back</a>
                    <?php endif; ?>
                    <h5 class="tutor-card-title" style="margin: 0; text-align: left;">
                        <?php echo htmlspecialchars($data['parentCourse'] ? $data['parentCourse']->title : 'Available Levels'); ?>
                    </h5>
                </div>
                <div class="tutor-card-body">
                    <?php if(empty($data['courses'])): ?>
                        <p>No courses found in this category.</p>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 1rem;">
                            <?php foreach($data['courses'] as $course): ?>
                                <div class="card" style="padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <h3 style="margin-top: 0; color: var(--text-main); font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($course->title); ?></h3>
                                    
                                    <?php if($course->is_leaf): ?>
                                        <form action="<?php echo URLROOT; ?>/student-dashboard/processEnrollment" method="POST">
                                            <input type="hidden" name="course_id" value="<?php echo $course->id; ?>">
                                            <button type="submit" class="btn btn-primary" style="width: 100%;">Enroll Now</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="<?php echo URLROOT; ?>/student-dashboard/enroll?parent_id=<?php echo $course->id; ?>" class="btn btn-outline-primary" style="width: 100%; display: block; text-align: center; text-decoration: none; box-sizing: border-box;">Select</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toast fade out
        const toasts = document.querySelectorAll('.toast-alert');
        toasts.forEach(t => {
            setTimeout(() => {
                t.style.opacity = '0';
                setTimeout(() => t.remove(), 300);
            }, 3000);
        });
    });
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
