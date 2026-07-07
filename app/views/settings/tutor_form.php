<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Top Section: Header and Detached Tab Pills -->
<div style="margin-bottom: 2rem;">
    <!-- Detached Tab Pills -->
    <ul class="nav-pills" id="tutorFormTabs"
        style="border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">
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
<div class="tab-content" style="width: 100%;">

    <!-- Create Sections Tab -->
    <div id="sections-tab" class="tab-pane active">

        <!-- Toast Notifications (Top Right) -->
        <div id="toast-container"
            style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem;">
            <?php if (!empty($data['success_msg'])): ?>
                <div class="toast-alert"
                    style="padding: 1rem 1.5rem; background-color: #dcfce7; color: #166534; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border-left: 4px solid #16a34a; transition: opacity 0.5s ease, transform 0.5s ease;">
                    <span style="font-weight: 600; margin-right: 0.5rem;">Success:</span><?php echo $data['success_msg']; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['section_err'])): ?>
                <div class="toast-alert"
                    style="padding: 1rem 1.5rem; background-color: #fee2e2; color: #991b1b; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border-left: 4px solid #dc2626; transition: opacity 0.5s ease, transform 0.5s ease;">
                    <span style="font-weight: 600; margin-right: 0.5rem;">Error:</span><?php echo $data['section_err']; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Two-Column Layout -->
        <div style="display: grid; grid-template-columns: 1.5fr 2.5fr; gap: 2.5rem; align-items: start;">

            <!-- Left: Create Section Card -->
            <div class="card" style="max-width: none;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem;">
                    <h5 id="formTitle" style="margin: 0; color: var(--text-main); font-size: 1rem; font-weight: 600;">
                        Add New Section</h5>
                </div>
                <div style="padding: 1.5rem;">
                    <form id="sectionForm" action="<?php echo URLROOT; ?>/settings/tutor_form" method="POST">
                        <input type="hidden" name="action" id="formAction" value="add_section">
                        <input type="hidden" name="section_id" id="formSectionId" value="">

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="section_name" id="sectionNameInput" class="form-control"
                                placeholder="e.g. Educational Background" maxlength="30"
                                value="<?php echo htmlspecialchars($data['section_name']); ?>">
                            <small
                                style="color: var(--text-muted); display: block; margin-top: 0.5rem; font-size: 0.8rem;">Max
                                30 characters. (<span id="charCount">0</span>/30)</small>
                        </div>

                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button type="submit" id="submitBtn" class="btn btn-primary" <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'disabled' : ''; ?>
                                style="flex: 1; min-width: 120px;">
                                <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'Limit Reached' : 'Add Section'; ?>
                            </button>
                            <button type="button" id="cancelBtn" class="btn"
                                style="display: none; background: #e5e7eb; color: #374151; border: 1px solid #d1d5db; flex: 1; min-width: 80px;"
                                onclick="cancelEditMode()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: List Section Card with Scroll -->
            <div class="card"
                style="display: flex; flex-direction: column; max-height: calc(100vh - 250px); min-height: 400px; max-width: none;">
                <div class="card-header"
                    style="border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; background-color: var(--card-bg); z-index: 10;">
                    <h5 style="margin: 0; color: var(--text-main); font-size: 1rem; font-weight: 600;">Existing Sections
                    </h5>
                    <span
                        style="background-color: var(--bg-color); padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600; color: var(--primary-color);">
                        <?php echo $data['sectionCount']; ?>/10
                    </span>
                </div>

                <!-- Scrollable Container -->
                <div style="overflow-y: auto; flex: 1; padding: 0;">
                    <?php if (empty($data['sections'])): ?>
                        <div style="padding: 3rem; text-align: center;">
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">No sections created yet.</p>
                        </div>
                    <?php else: ?>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead
                                style="position: sticky; top: 0; background: var(--card-bg); box-shadow: 0 1px 2px rgba(0,0,0,0.05); z-index: 5;">
                                <tr>
                                    <th
                                        style="text-align: left; padding: 1rem 1.5rem; color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Section Name</th>
                                    <th
                                        style="text-align: right; padding: 1rem 1.5rem; color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['sections'] as $index => $section): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color); transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='var(--bg-color)'"
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 1.25rem 1.5rem; font-weight: 500; color: var(--text-main);">
                                            <?php echo htmlspecialchars($section->sectionName); ?>
                                        </td>
                                        <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                            <button type="button"
                                                onclick="editSection(<?php echo $section->id; ?>, '<?php echo addslashes(htmlspecialchars($section->sectionName)); ?>')"
                                                style="background: none; border: none; cursor: pointer; padding: 0.5rem; color: #3b82f6; transition: transform 0.1s;"
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
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this section?');">
                                                    <input type="hidden" name="action" value="delete_section">
                                                    <input type="hidden" name="section_id" value="<?php echo $section->id; ?>">
                                                    <button type="submit"
                                                        style="background: none; border: none; cursor: pointer; padding: 0.5rem; color: #ef4444; transition: transform 0.1s;"
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
        <div class="card" style="padding: 2rem; max-width: none;">
            <h4 style="margin-bottom: 0.5rem; color: var(--text-main); font-size: 1.2rem;">Manage Filters</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Define the filters that users
                can use to search for tutors (e.g., Subject, City, Ratings).</p>

            <form action="#" method="POST">
                <div class="form-group" style="max-width: 400px;">
                    <label class="form-label">Filter Name</label>
                    <input type="text" class="form-control" placeholder="e.g. Preferred Subject">
                </div>
                <div class="form-group" style="max-width: 400px;">
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
        <div class="card" style="padding: 2rem; max-width: none;">
            <h4 style="margin-bottom: 0.5rem; color: var(--text-main); font-size: 1.2rem;">Form Fields Builder</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Add specific input fields to
                your previously created sections.</p>

            <form action="#" method="POST">
                <div class="form-group" style="max-width: 400px;">
                    <label class="form-label">Field Name</label>
                    <input type="text" class="form-control" placeholder="e.g. Years of Experience">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-width: 800px;">
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
    });
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>