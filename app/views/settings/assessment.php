<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Top Section -->
<div class="tutor-form-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <ul class="nav-pills tutor-form-tabs" id="tutorFormTabs" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active">Upload Assessment Questions</a>
        </li>
    </ul>
</div>

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
        <div class="tutor-form-container">
            <div class="card tutor-card" style="width: 100%; max-width: 800px; margin: 0 auto;">
                <div class="card-header tutor-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h5 class="tutor-card-title">Upload Questions (CSV)</h5>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="<?php echo URLROOT; ?>/settings/assessment_questions" target="_blank" class="btn btn-sm btn-info">Questions</a>
                        <a href="data:text/csv;charset=utf-8,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks%0A%22What%20is%202%2B2%3F%22,%223%22,%224%22,%225%22,%226%22,%22option_b%22,%22Because%202%2B2%3D4%22,1" download="sample_assessment.csv" class="btn btn-sm btn-outline-primary">Download Sample CSV</a>
                    </div>
                </div>
                <div class="tutor-card-body">
                    <form action="<?php echo URLROOT; ?>/settings/assessment" method="POST" enctype="multipart/form-data" id="assessmentForm">
                        
                        <div id="dynamic-dropdowns-container">
                            <!-- Dropdowns will be generated here by JS -->
                        </div>

                        <input type="hidden" name="course_id" id="final_course_id" value="">

                        <div class="form-group tutor-form-group" style="margin-top: 1.5rem;">
                            <label class="form-label">CSV File</label>
                            <input type="file" name="assessment_csv" class="form-control" accept=".csv" required>
                            <small class="tutor-char-count">Please ensure your CSV matches the sample format with 8 columns.</small>
                        </div>

                        <div class="tutor-btn-group" style="margin-top: 2rem;">
                            <button type="submit" id="submitBtn" class="btn btn-primary tutor-btn-submit" disabled>
                                Upload Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const courses = <?php echo json_encode($data['courses'] ?: []); ?>;
    const maxLevel = Math.max(...courses.map(c => parseInt(c.level) || 0), 0);
    const dropdownContainer = document.getElementById('dynamic-dropdowns-container');
    const finalCourseId = document.getElementById('final_course_id');
    const submitBtn = document.getElementById('submitBtn');

    function renderDropdown(level, parentId = null) {
        // Remove any dropdowns >= current level
        const existingDropdowns = dropdownContainer.querySelectorAll('.course-level-select');
        existingDropdowns.forEach(dd => {
            if (parseInt(dd.dataset.level) >= level) {
                dd.closest('.form-group').remove();
            }
        });

        // Get courses for this level and parent
        let levelCourses = courses.filter(c => parseInt(c.level) === level);
        if (parentId !== null) {
            levelCourses = levelCourses.filter(c => c.parent_id == parentId);
        }

        if (levelCourses.length === 0) {
            // No children found, the previous selection is a leaf node (the "last level")
            if (parentId !== null) {
                finalCourseId.value = parentId;
                submitBtn.disabled = false;
            }
            return;
        }

        // Reset final course id and submit button since we need further selection
        finalCourseId.value = '';
        submitBtn.disabled = true;

        // Create new dropdown
        const formGroup = document.createElement('div');
        formGroup.className = 'form-group tutor-form-group';
        formGroup.style.marginBottom = '1rem';
        
        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = `Select Level ${level}`;
        
        const select = document.createElement('select');
        select.className = 'form-control course-level-select';
        select.dataset.level = level;
        select.required = true;
        
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = 'Select...';
        select.appendChild(defaultOpt);
        
        levelCourses.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.title;
            select.appendChild(opt);
        });
        
        select.addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId) {
                // Check if this selected item has children
                const hasChildren = courses.some(c => c.parent_id == selectedId);
                if (hasChildren) {
                    renderDropdown(level + 1, selectedId);
                } else {
                    // This is a leaf node
                    // Clear deeper dropdowns
                    const deeperDropdowns = dropdownContainer.querySelectorAll('.course-level-select');
                    deeperDropdowns.forEach(dd => {
                        if (parseInt(dd.dataset.level) > level) {
                            dd.closest('.form-group').remove();
                        }
                    });
                    finalCourseId.value = selectedId;
                    submitBtn.disabled = false;
                }
            } else {
                // If they re-select "Select...", clear children
                const deeperDropdowns = dropdownContainer.querySelectorAll('.course-level-select');
                deeperDropdowns.forEach(dd => {
                    if (parseInt(dd.dataset.level) > level) {
                        dd.closest('.form-group').remove();
                    }
                });
                finalCourseId.value = '';
                submitBtn.disabled = true;
            }
        });

        formGroup.appendChild(label);
        formGroup.appendChild(select);
        dropdownContainer.appendChild(formGroup);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Init first level dropdown
        renderDropdown(1);

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
