<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Top Section: Header and Detached Tab Pills -->
<div class="tutor-form-header">
    <!-- Detached Tab Pills -->
    <ul class="nav-pills tutor-form-tabs" id="tutorFormTabs">
        <li class="nav-item">
            <a class="nav-link active" onclick="switchTab(event, 'sections-tab')">Create Sections</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="switchTab(event, 'filters-tab')">Create Filters</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="switchTab(event, 'form-tab')">Create Form</a>
        </li>
    </ul>
</div>

<!-- Tab Content Area (No Outer Card) -->
<div class="tab-content tutor-form-content">

    <!-- Create Sections Tab -->
    <div id="sections-tab" class="tab-pane active">

        <!-- Toast Notifications (Top Right) -->
        <div id="toast-container" class="toast-container">
            <?php if (!empty($data['success_msg'])): ?>
                <div class="toast-alert toast-success">
                    <span class="toast-message-bold">Success:</span><?php echo $data['success_msg']; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['section_err'])): ?>
                <div class="toast-alert toast-error">
                    <span class="toast-message-bold">Error:</span><?php echo $data['section_err']; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Two-Column Layout -->
        <div class="tutor-form-container">

            <!-- Left: Create Section Card -->
            <div class="card tutor-card">
                <div class="card-header tutor-card-header">
                    <h5 id="formTitle" class="tutor-card-title">
                        Add New Form Section</h5>
                </div>
                <div class="tutor-card-body">
                    <form id="sectionForm" action="<?php echo URLROOT; ?>/settings/tutor_form" method="POST">
                        <input type="hidden" name="action" id="formAction" value="add_section">
                        <input type="hidden" name="section_id" id="formSectionId" value="">

                        <div class="form-group tutor-form-group">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="section_name" id="sectionNameInput" class="form-control"
                                placeholder="e.g. Educational Background" maxlength="30"
                                value="<?php echo htmlspecialchars($data['section_name']); ?>">
                            <small class="tutor-char-count">Max
                                30 characters. (<span id="charCount">0</span>/30)</small>
                        </div>

                        <div class="tutor-btn-group">
                            <button type="submit" id="submitBtn" class="btn btn-primary tutor-btn-submit" <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'disabled' : ''; ?>>
                                <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'Limit Reached' : 'Add Section'; ?>
                            </button>
                            <button type="button" id="cancelBtn" class="btn tutor-btn-cancel"
                                onclick="cancelEditMode()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: List Section Card with Scroll -->
            <div class="card tutor-sections-list-card">
                <div class="card-header tutor-sections-header">
                    <h5 class="tutor-card-title">Form Sections List</h5>
                    <span class="tutor-sections-count">
                        <?php echo $data['sectionCount']; ?>/10
                    </span>
                </div>

                <!-- Scrollable Container -->
                <div class="tutor-sections-container">
                    <?php if (empty($data['sections'])): ?>
                        <div class="tutor-sections-empty">
                            <p class="tutor-sections-empty-text">No sections created yet.</p>
                        </div>
                    <?php else: ?>
                        <table class="tutor-sections-table">
                            <thead class="tutor-sections-thead">
                                <tr>
                                    <th class="tutor-sections-th-empty"></th>
                                    <th class="tutor-sections-th">Section Name</th>
                                    <th class="tutor-sections-th-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['sections'] as $index => $section): ?>
                                    <tr class="tutor-sections-tr draggable-row"
                                        onmouseover="this.style.backgroundColor='var(--bg-color)'"
                                        onmouseout="this.style.backgroundColor='transparent'" draggable="true"
                                        data-id="<?php echo $section->id; ?>">
                                        <td class="drag-handle">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8-12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                                            </svg>
                                        </td>
                                        <td class="tutor-sections-td">
                                            <?php echo htmlspecialchars($section->sectionName); ?>
                                        </td>
                                        <td class="tutor-sections-td-actions">
                                            <button type="button"
                                                onclick="editSection(<?php echo $section->id; ?>, '<?php echo addslashes(htmlspecialchars($section->sectionName)); ?>')"
                                                class="action-btn-edit"
                                                onmouseover="this.style.transform='scale(1.1)'"
                                                onmouseout="this.style.transform='scale(1)'" title="Edit Section">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <?php if ($section->canDelete): ?>
                                                <form action="<?php echo URLROOT; ?>/settings/tutor_form" method="POST"
                                                    class="action-form-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this section?');">
                                                    <input type="hidden" name="action" value="delete_section">
                                                    <input type="hidden" name="section_id" value="<?php echo $section->id; ?>">
                                                    <button type="submit"
                                                        class="action-btn-delete"
                                                        onmouseover="this.style.transform='scale(1.1)'"
                                                        onmouseout="this.style.transform='scale(1)'" title="Delete Section">
                                                        <svg width="18" height="18" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Create Filters Tab -->
    <div id="filters-tab" class="tab-pane">
        <div class="card tutor-manage-card">
            <h4 class="tutor-manage-title">Manage Filters</h4>
            <p class="tutor-manage-desc">Define the filters that users
                can use to search for tutors (e.g., Subject, City, Ratings).</p>

            <form action="#" method="POST">
                <div class="form-group input-max-400">
                    <label class="form-label">Filter Name</label>
                    <input type="text" class="form-control" placeholder="e.g. Preferred Subject">
                </div>
                <div class="form-group input-max-400">
                    <label class="form-label">Filter Type</label>
                    <select class="form-control">
                        <option>Dropdown Selection</option>
                        <option>Checkbox Group</option>
                        <option>Range Slider</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="alert('UI Mockup: Filter added!')">Add
                    Filter</button>
            </form>
        </div>
    </div>

    <!-- Create Form Tab -->
    <div id="form-tab" class="tab-pane">
        <div class="card tutor-manage-card">
            <h4 class="tutor-manage-title">Form Fields Builder</h4>
            <p class="tutor-manage-desc">Add specific input fields to
                your previously created sections.</p>

            <form action="#" method="POST">
                <div class="form-group input-max-400">
                    <label class="form-label">Field Name</label>
                    <input type="text" class="form-control" placeholder="e.g. Years of Experience">
                </div>
                <div class="input-grid-800">
                    <div class="form-group">
                        <label class="form-label">Input Type</label>
                        <select class="form-control">
                            <option>Text Input</option>
                            <option>Number Input</option>
                            <option>Textarea</option>
                            <option>Dropdown</option>
                            <option>File Upload</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign to Section</label>
                        <select class="form-control">
                            <option disabled selected>Select a section...</option>
                            <?php if (!empty($data['sections'])): ?>
                                <?php foreach ($data['sections'] as $section): ?>
                                    <option><?php echo htmlspecialchars($section->sectionName); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>No sections available.</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="alert('UI Mockup: Field added!')">Add
                    Field</button>
            </form>
        </div>
    </div>

</div>

<script>
    // Tab Switching
    function switchTab(event, tabId) {
        event.preventDefault();
        const links = document.querySelectorAll('.nav-pills .nav-link');
        links.forEach(link => link.classList.remove('active'));
        event.currentTarget.classList.add('active');

        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(pane => pane.classList.remove('active'));

        const targetPane = document.getElementById(tabId);
        if (targetPane) {
            targetPane.classList.add('active');
        }
    }

    // Character Counter
    const sectionInput = document.getElementById('sectionNameInput');
    const charCount = document.getElementById('charCount');
    if (sectionInput && charCount) {
        sectionInput.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });
        // Init on load
        charCount.textContent = sectionInput.value.length;
    }

    // Populate Edit Form
    function editSection(id, name) {
        document.getElementById('formTitle').textContent = 'Edit Section';
        document.getElementById('formAction').value = 'edit_section';
        document.getElementById('formSectionId').value = id;

        sectionInput.value = name;
        charCount.textContent = name.length;

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.textContent = 'Update Section';
        submitBtn.disabled = false;

        document.getElementById('cancelBtn').style.display = 'block';
    }

    // Cancel Edit Mode
    function cancelEditMode() {
        document.getElementById('formTitle').textContent = 'Add New Section';
        document.getElementById('formAction').value = 'add_section';
        document.getElementById('formSectionId').value = '';

        sectionInput.value = '';
        charCount.textContent = '0';

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.textContent = 'Add Section';

        <?php if ($data['sectionCount'] >= 10): ?>
            submitBtn.disabled = true;
            submitBtn.textContent = 'Limit Reached (10/10)';
        <?php endif; ?>

        document.getElementById('cancelBtn').style.display = 'none';
    }

    // Auto-dismiss Toasts after 3 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const toasts = document.querySelectorAll('.toast-alert');
        if (toasts.length > 0) {
            setTimeout(() => {
                toasts.forEach(toast => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 500); // Remove from DOM after fade out
                });
            }, 3000);
        }

        // Drag and Drop functionality
        const tableBody = document.querySelector('tbody');
        let draggedRow = null;

        if (tableBody) {
            tableBody.addEventListener('dragstart', function (e) {
                const row = e.target.closest('tr');
                if (row && row.classList.contains('draggable-row')) {
                    draggedRow = row;
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', draggedRow.innerHTML);
                    setTimeout(() => { draggedRow.style.opacity = '0.5'; }, 0);
                }
            });

            tableBody.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                const targetRow = e.target.closest('tr');
                if (targetRow && targetRow !== draggedRow && targetRow.classList.contains('draggable-row')) {
                    const rect = targetRow.getBoundingClientRect();
                    const offset = e.clientY - rect.top;
                    if (offset > rect.height / 2) {
                        targetRow.after(draggedRow);
                    } else {
                        targetRow.before(draggedRow);
                    }
                }
            });

            tableBody.addEventListener('dragend', function (e) {
                if (draggedRow) {
                    draggedRow.style.opacity = '1';
                    draggedRow = null;
                    updateOrder();
                }
            });
        }

        function updateOrder() {
            const rows = document.querySelectorAll('.draggable-row');
            const order = Array.from(rows).map(row => row.getAttribute('data-id'));

            const formData = new FormData();
            formData.append('action', 'reorder_sections');
            order.forEach((id, index) => {
                formData.append(`order[${index}]`, id);
            });

            fetch('<?php echo URLROOT; ?>/settings/tutor_form', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const toastContainer = document.getElementById('toast-container');
                        if (toastContainer) {
                            const toast = document.createElement('div');
                            toast.className = 'toast-alert toast-success';
                            toast.innerHTML = '<span class="toast-message-bold">Success:</span>Section order updated successfully!';
                            toastContainer.appendChild(toast);
                            setTimeout(() => {
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateY(-10px)';
                                setTimeout(() => toast.remove(), 500);
                            }, 3000);
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>