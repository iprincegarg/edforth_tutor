<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Top Section -->
<div class="tutor-form-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <ul class="nav-pills tutor-form-tabs" id="tutorFormTabs" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active">Create Course Hierarchy</a>
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
    <?php if (!empty($data['course_err'])): ?>
        <div class="toast-alert toast-error">
            <span class="toast-message-bold">Error:</span><?php echo $data['course_err']; ?>
        </div>
    <?php endif; ?>
</div>

<div class="tab-content tutor-form-content">
    <div class="tab-pane active">
        <!-- Two-Column Layout -->
        <div class="tutor-form-container">
            <!-- Left: Form -->
            <div class="card tutor-card">
                <div class="card-header tutor-card-header">
                    <h5 id="formTitle" class="tutor-card-title">Add New Course Level</h5>
                </div>
                <div class="tutor-card-body">
                    <form id="courseForm" action="<?php echo URLROOT; ?>/settings/create_course" method="POST">
                        <input type="hidden" name="action" id="formAction" value="add_course">
                        <input type="hidden" name="course_id" id="formCourseId" value="">

                        <div class="form-group tutor-form-group">
                            <label class="form-label">Course Title</label>
                            <input type="text" name="title" id="titleInput" class="form-control"
                                placeholder="e.g. GCSE" maxlength="30"
                                value="<?php echo htmlspecialchars($data['form_data']['title'] ?? ''); ?>" required>
                            <small class="tutor-char-count">Max 30 characters.</small>
                        </div>

                        <div class="form-group tutor-form-group">
                            <label class="form-label">Level</label>
                            <select name="level" id="levelSelect" class="form-control" required onchange="handleLevelChange()">
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($data['form_data']['level']) && $data['form_data']['level'] == $i) ? 'selected' : ''; ?>>Level <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group tutor-form-group">
                            <label class="form-label">Parent Title</label>
                            <select name="parent_id" id="parentSelect" class="form-control" disabled>
                                <option value="">Select Parent...</option>
                                <!-- Populated dynamically by JS -->
                            </select>
                            <small class="tutor-char-count" id="parentHelper">Parent title is required for Level 2 and above.</small>
                        </div>

                        <div class="tutor-btn-group">
                            <button type="submit" id="submitBtn" class="btn btn-primary tutor-btn-submit">
                                Add Course
                            </button>
                            <button type="button" id="cancelBtn" class="btn tutor-btn-cancel"
                                onclick="cancelEditMode()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Menu -->
            <div class="card tutor-sections-list-card">
                <div class="card-header tutor-sections-header">
                    <h5 class="tutor-card-title">Course Hierarchy Menu</h5>
                </div>
                <div class="tutor-sections-container" style="padding: 1rem;">
                    <?php if (empty($data['courses'])): ?>
                        <div class="tutor-sections-empty">
                            <p class="tutor-sections-empty-text">No courses created yet.</p>
                        </div>
                    <?php else: ?>
                        <!-- Recursive function to display hierarchy -->
                        <?php
                        function buildTree(array $elements, $parentId = null) {
                            $branch = array();
                            foreach ($elements as $element) {
                                if ($element->parent_id == $parentId) {
                                    $children = buildTree($elements, $element->id);
                                    if ($children) {
                                        $element->children = $children;
                                    }
                                    $branch[] = $element;
                                }
                            }
                            return $branch;
                        }
                        
                        function renderTree($tree) {
                            echo '<ul style="list-style: none; padding-left: 20px; border-left: 1px dashed #cbd5e1; margin-top: 0.5rem;">';
                            foreach ($tree as $node) {
                                echo '<li style="margin-bottom: 0.5rem; position: relative;">';
                                echo '<div style="display: flex; align-items: center; gap: 0.5rem;">';
                                echo '<span style="font-weight: 500; color: var(--text-main);">' . htmlspecialchars($node->title) . ' <span style="font-size: 0.75rem; color: var(--text-muted); background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">Level ' . $node->level . '</span></span>';
                                
                                // Actions
                                $nodeJson = htmlspecialchars(json_encode([
                                    'id' => $node->id,
                                    'title' => $node->title,
                                    'level' => $node->level,
                                    'parent_id' => $node->parent_id
                                ]), ENT_QUOTES, 'UTF-8');
                                
                                echo '
                                <button type="button" onclick="editCourse('.$nodeJson.')" style="background:none; border:none; color: var(--primary); cursor: pointer;" title="Edit">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form action="'.URLROOT.'/settings/create_course" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure? This will delete all child courses as well.\');">
                                    <input type="hidden" name="action" value="delete_course">
                                    <input type="hidden" name="course_id" value="'.$node->id.'">
                                    <button type="submit" style="background:none; border:none; color: var(--error-color); cursor: pointer;" title="Delete">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                ';
                                echo '</div>';
                                
                                if (!empty($node->children)) {
                                    renderTree($node->children);
                                }
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        
                        $tree = buildTree($data['courses']);
                        if (empty($tree)) {
                            // If somehow tree building fails or orphans exist
                            echo '<p class="tutor-sections-empty-text">Orphaned items found or empty tree.</p>';
                        } else {
                            renderTree($tree);
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // All courses passed from PHP to JS
    const courses = <?php echo json_encode($data['courses'] ?: []); ?>;

    function handleLevelChange(selectedParentId = null) {
        const levelSelect = document.getElementById('levelSelect');
        const parentSelect = document.getElementById('parentSelect');
        const level = parseInt(levelSelect.value);

        parentSelect.innerHTML = '<option value="">Select Parent...</option>';

        if (level === 1) {
            parentSelect.disabled = true;
            parentSelect.required = false;
        } else {
            parentSelect.disabled = false;
            parentSelect.required = true;
            
            // Filter courses that are exactly Level - 1
            const parentCandidates = courses.filter(c => parseInt(c.level) === (level - 1));
            
            parentCandidates.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.title;
                if (selectedParentId && c.id == selectedParentId) {
                    opt.selected = true;
                }
                parentSelect.appendChild(opt);
            });
        }
    }

    function editCourse(course) {
        document.getElementById('formTitle').textContent = 'Edit Course';
        document.getElementById('formAction').value = 'edit_course';
        document.getElementById('formCourseId').value = course.id;
        document.getElementById('titleInput').value = course.title;
        document.getElementById('levelSelect').value = course.level;
        document.getElementById('submitBtn').textContent = 'Update Course';
        
        handleLevelChange(course.parent_id);
    }

    function cancelEditMode() {
        document.getElementById('formTitle').textContent = 'Add New Course Level';
        document.getElementById('formAction').value = 'add_course';
        document.getElementById('formCourseId').value = '';
        document.getElementById('titleInput').value = '';
        document.getElementById('levelSelect').value = '1';
        document.getElementById('submitBtn').textContent = 'Add Course';
        
        handleLevelChange();
    }

    // Initialize state on load
    document.addEventListener('DOMContentLoaded', () => {
        // If there was a validation error, re-select the level to populate parents correctly
        const currentParentId = "<?php echo $data['form_data']['parent_id'] ?? ''; ?>";
        handleLevelChange(currentParentId);

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
