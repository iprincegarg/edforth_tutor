<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<!-- Top Section: Header and Detached Tab Pills -->
<div class="student-form-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <!-- Detached Tab Pills -->
    <ul class="nav-pills student-form-tabs" id="studentFormTabs" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active" onclick="switchTab(event, 'sections-tab')">Create Sections</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="switchTab(event, 'form-tab')">Create Form</a>
        </li>
    </ul>
    <div>
        <a href="<?php echo URLROOT; ?>/register-as-student" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 14px; line-height: 1.5; border-radius: 4px; white-space: nowrap;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            Access Form
        </a>
    </div>
</div>

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
    <?php if (!empty($data['filter_err'])): ?>
        <div class="toast-alert toast-error">
            <span class="toast-message-bold">Error:</span><?php echo $data['filter_err']; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($data['field_err'])): ?>
        <div class="toast-alert toast-error">
            <span class="toast-message-bold">Error:</span><?php echo $data['field_err']; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Tab Content Area (No Outer Card) -->
<div class="tab-content student-form-content">

    <!-- Create Sections Tab -->
    <div id="sections-tab" class="tab-pane active">

        <!-- Two-Column Layout -->
        <div class="student-form-container">

            <!-- Left: Create Section Card -->
            <div class="card student-card">
                <div class="card-header student-card-header">
                    <h5 id="formTitle" class="student-card-title">
                        Add New Form Section</h5>
                </div>
                <div class="student-card-body">
                    <form id="sectionForm" action="<?php echo URLROOT; ?>/settings/student_form" method="POST">
                        <input type="hidden" name="action" id="formAction" value="add_section">
                        <input type="hidden" name="section_id" id="formSectionId" value="">

                        <div class="form-group student-form-group">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="section_name" id="sectionNameInput" class="form-control"
                                placeholder="e.g. Educational Background" maxlength="30"
                                value="<?php echo htmlspecialchars($data['section_name']); ?>">
                            <small class="student-char-count">Max
                                30 characters. (<span id="charCount">0</span>/30)</small>
                        </div>

                        <div class="student-btn-group">
                            <button type="submit" id="submitBtn" class="btn btn-primary student-btn-submit" <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'disabled' : ''; ?>>
                                <?php echo ($data['sectionCount'] >= 10 && empty($data['section_name'])) ? 'Limit Reached' : 'Add Section'; ?>
                            </button>
                            <button type="button" id="cancelBtn" class="btn student-btn-cancel"
                                onclick="cancelEditMode()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: List Section Card with Scroll -->
            <div class="card student-sections-list-card">
                <div class="card-header student-sections-header">
                    <h5 class="student-card-title">Form Sections List</h5>
                    <span class="student-sections-count">
                        <?php echo $data['sectionCount']; ?>/10
                    </span>
                </div>

                <!-- Scrollable Container -->
                <div class="student-sections-container">
                    <?php if (empty($data['sections'])): ?>
                        <div class="student-sections-empty">
                            <p class="student-sections-empty-text">No sections created yet.</p>
                        </div>
                    <?php else: ?>
                        <table class="student-sections-table">
                            <thead class="student-sections-thead">
                                <tr>
                                    <th class="student-sections-th-empty"></th>
                                    <th class="student-sections-th">Section Name</th>
                                    <th class="student-sections-th-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['sections'] as $index => $section): ?>
                                    <tr class="student-sections-tr draggable-row"
                                        onmouseover="this.style.backgroundColor='var(--bg-color)'"
                                        onmouseout="this.style.backgroundColor='transparent'" draggable="true"
                                        data-id="<?php echo $section->id; ?>">
                                        <td class="drag-handle">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8-12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                                            </svg>
                                        </td>
                                        <td class="student-sections-td">
                                            <?php echo htmlspecialchars($section->sectionName); ?>
                                        </td>
                                        <td class="student-sections-td-actions">
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
                                                <form action="<?php echo URLROOT; ?>/settings/student_form" method="POST"
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

    <!-- Create Form Tab -->
    <div id="form-tab" class="tab-pane">
        <div class="student-form-container">
            <!-- Left: Create Field Card -->
            <div class="card student-card">
                <div class="card-header student-card-header">
                    <h5 id="fieldFormTitle" class="student-card-title">Add New Field</h5>
                </div>
                <div class="student-card-body">
                    <form id="fieldForm" action="<?php echo URLROOT; ?>/settings/student_form" method="POST">
                        <input type="hidden" name="action" id="fieldFormAction" value="add_field">
                        <input type="hidden" name="field_id" id="fieldFormId" value="">

                        <div class="form-group student-form-group">
                            <label class="form-label">Assign to Section <span style="color:red">*</span></label>
                            <select name="section_id" id="fieldSectionId" class="form-control" required>
                                <option value="" disabled selected>Select a section...</option>
                                <?php if (!empty($data['sections'])): ?>
                                    <?php foreach ($data['sections'] as $section): ?>
                                        <option value="<?php echo $section->id; ?>" <?php echo (isset($data['field_form_data']['section_id']) && $data['field_form_data']['section_id'] == $section->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($section->sectionName); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No sections available.</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group student-form-group">
                            <label class="form-label">Field Name <span style="color:red">*</span></label>
                            <input type="text" name="field_name" id="fieldNameInput" class="form-control"
                                placeholder="e.g. Years of Experience" maxlength="30"
                                value="<?php echo htmlspecialchars($data['field_form_data']['field_name'] ?? ''); ?>" required>
                            <small class="student-char-count">Max 30 characters. (<span id="fieldCharCount">0</span>/30)</small>
                        </div>

                        <div class="form-group student-form-group">
                            <label class="form-label">Field Type <span style="color:red">*</span></label>
                            <select name="field_type" id="fieldTypeSelect" class="form-control" onchange="onFieldTypeChange()" required>
                                <option value="" disabled selected>Select a field type...</option>
                                <?php 
                                $types = ['text' => 'Text Input', 'radio' => 'Radio Buttons', 'file' => 'File Upload', 'textarea' => 'Text Area', 'dropdown' => 'Dropdown Selection'];
                                $selectedType = $data['field_form_data']['field_type'] ?? '';
                                foreach ($types as $val => $label) {
                                    $sel = ($selectedType === $val) ? 'selected' : '';
                                    echo "<option value=\"$val\" $sel>$label</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Dynamic Config: Placeholder -->
                        <div class="form-group student-form-group dynamic-config" id="config-placeholder" style="display:none;">
                            <label class="form-label">Placeholder Text</label>
                            <input type="text" name="placeholder_text" id="fieldPlaceholderText" class="form-control" placeholder="e.g. Enter your experience..." value="<?php echo htmlspecialchars($data['field_form_data']['placeholder_text'] ?? ''); ?>">
                        </div>

                        <!-- Dynamic Config: Field Values -->
                        <div class="form-group student-form-group dynamic-config" id="config-values" style="display:none;">
                            <label class="form-label">Field Values (Comma Separated) <span style="color:red">*</span></label>
                            <textarea name="field_values" id="fieldValuesInput" class="form-control" rows="2" placeholder="e.g. Option 1, Option 2, Option 3"><?php echo htmlspecialchars($data['field_form_data']['field_values'] ?? ''); ?></textarea>
                            <small class="student-char-count" style="text-align: left;">Limit: Max 50 characters per value.</small>
                        </div>

                        <!-- Dynamic Config: Char Limit -->
                        <div class="form-group student-form-group dynamic-config" id="config-charlimit" style="display:none;">
                            <label class="form-label">Character Limit (Optional)</label>
                            <input type="number" name="char_limit" id="fieldCharLimit" class="form-control" placeholder="e.g. 100" min="1" value="<?php echo htmlspecialchars($data['field_form_data']['char_limit'] ?? ''); ?>">
                            <small class="student-char-count" style="text-align: left;">Leave blank for default limits.</small>
                        </div>

                        <!-- Dynamic Config: File Note -->
                        <div class="form-group student-form-group dynamic-config" id="config-file" style="display:none;">
                            <div style="background-color: #f3f4f6; border-left: 4px solid #6b7280; padding: 0.75rem; border-radius: 4px; font-size: 0.85rem; color: #374151;">
                                <strong>File Upload Settings:</strong><br>
                                Accepts: pdf, png, jpg, jpeg, zip<br>
                                Max Size: 5MB<br>
                                <em>Note: Maximum of 5 file upload fields allowed across the entire form.</em>
                            </div>
                        </div>
                        
                        <!-- Toggle Settings -->
                        <div class="student-form-group" style="display: flex; flex-direction: column; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                            <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                                <span>Is Required?</span>
                                <input type="checkbox" name="is_required" id="fieldIsRequired" value="1" <?php echo (!isset($data['field_form_data']) || !empty($data['field_form_data']['is_required'])) ? 'checked' : ''; ?>>
                            </label>
                            <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                                <span>Show on Form?</span>
                                <input type="checkbox" name="show_on_form" id="fieldShowOnForm" value="1" <?php echo (!isset($data['field_form_data']) || !empty($data['field_form_data']['show_on_form'])) ? 'checked' : ''; ?>>
                            </label>
                            <label style="display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                                <span>Show to User in Profile?</span>
                                <input type="checkbox" name="show_to_user" id="fieldShowToUser" value="1" <?php echo (!isset($data['field_form_data']) || !empty($data['field_form_data']['show_to_user'])) ? 'checked' : ''; ?>>
                            </label>
                        </div>

                        <div class="student-btn-group" style="margin-top: 1.5rem;">
                            <button type="submit" id="fieldSubmitBtn" class="btn btn-primary student-btn-submit">Add Field</button>
                            <button type="button" id="fieldCancelBtn" class="btn student-btn-cancel" onclick="cancelFieldEditMode()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: List Fields Card -->
            <div class="card student-sections-list-card">
                <div class="card-header student-sections-header">
                    <h5 class="student-card-title">Form Fields List</h5>
                    <span class="student-sections-count">
                        <?php echo count($data['fields']); ?>
                    </span>
                </div>

                <!-- Scrollable Container -->
                <div class="student-sections-container">
                    <?php if (empty($data['fields'])): ?>
                        <div class="student-sections-empty">
                            <p class="student-sections-empty-text">No fields created yet.</p>
                        </div>
                    <?php else: ?>
                        <table class="student-sections-table">
                            <thead class="student-sections-thead">
                                <tr>
                                    <th class="student-sections-th-empty"></th>
                                    <th class="student-sections-th" style="width: 20%">Section</th>
                                    <th class="student-sections-th" style="width: 25%">Field Name</th>
                                    <th class="student-sections-th" style="width: 15%">Type</th>
                                    <th class="student-sections-th" style="width: 25%">Details</th>
                                    <th class="student-sections-th-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['fields'] as $field): ?>
                                    <tr class="student-sections-tr draggable-field-row"
                                        onmouseover="this.style.backgroundColor='var(--bg-color)'"
                                        onmouseout="this.style.backgroundColor='transparent'" draggable="true"
                                        data-id="<?php echo $field->id; ?>">
                                        <td class="drag-handle">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8-12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                                            </svg>
                                        </td>
                                        <td class="student-sections-td" style="font-size: 0.85rem; width: 20%">
                                            <?php echo htmlspecialchars($field->sectionName); ?>
                                        </td>
                                        <td class="student-sections-td" style="width: 25%">
                                            <?php echo htmlspecialchars($field->field_name); ?>
                                            <?php if ($field->is_required): ?>
                                                <span style="color:red; font-size:0.8rem; margin-left: 2px;">*</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="student-sections-td" style="width: 15%; font-size: 0.85rem;">
                                            <span style="background: #eef2ff; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600; text-transform: capitalize;">
                                                <?php echo htmlspecialchars($field->field_type); ?>
                                            </span>
                                        </td>
                                        <td class="student-sections-td" style="width: 25%; font-size: 0.8rem; color: var(--text-muted);">
                                            <?php if ($field->field_type === 'filter'): ?>
                                                Filter: <?php echo htmlspecialchars($field->filterName ?? 'Unknown'); ?>
                                            <?php elseif ($field->field_type === 'radio' || $field->field_type === 'dropdown'): ?>
                                                <?php 
                                                $vals = array_filter(array_map('trim', explode(',', $field->field_values)));
                                                echo count($vals) . ' options'; 
                                                ?>
                                            <?php elseif ($field->field_type === 'text' || $field->field_type === 'textarea'): ?>
                                                Limit: <?php echo $field->char_limit ? $field->char_limit : 'Default'; ?>
                                            <?php elseif ($field->field_type === 'file'): ?>
                                                Max 5MB
                                            <?php endif; ?>
                                        </td>
                                        <td class="student-sections-td-actions">
                                            <button type="button"
                                                onclick='editField(<?php echo json_encode($field); ?>)'
                                                class="action-btn-edit"
                                                onmouseover="this.style.transform='scale(1.1)'"
                                                onmouseout="this.style.transform='scale(1)'" title="Edit Field">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <?php if (!isset($field->is_deletable) || $field->is_deletable == 1): ?>
                                            <form action="<?php echo URLROOT; ?>/settings/student_form" method="POST"
                                                class="action-form-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this field?');">
                                                <input type="hidden" name="action" value="delete_field">
                                                <input type="hidden" name="field_id" value="<?php echo $field->id; ?>">
                                                <button type="submit" class="action-btn-delete"
                                                    onmouseover="this.style.transform='scale(1.1)'"
                                                    onmouseout="this.style.transform='scale(1)'" title="Delete Field">
                                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <div class="action-btn-delete" style="color: #ccc; cursor: not-allowed;" title="This field cannot be deleted">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
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

</div>

<script>
    // Tab Switching
    function switchTab(event, tabId) {
        if (event) event.preventDefault();
        const links = document.querySelectorAll('.nav-pills .nav-link');
        links.forEach(link => link.classList.remove('active'));
        
        // Find the link that corresponds to this tabId
        let activeLink = null;
        if (event && event.currentTarget) {
            activeLink = event.currentTarget;
        } else {
            // Find it by traversing
            links.forEach(link => {
                if(link.getAttribute('onclick').includes(tabId)) {
                    activeLink = link;
                }
            });
        }
        if(activeLink) activeLink.classList.add('active');

        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(pane => pane.classList.remove('active'));

        const targetPane = document.getElementById(tabId);
        if (targetPane) {
            targetPane.classList.add('active');
        }
        
        // Update hash
        if(tabId === 'filters-tab') {
            window.location.hash = 'filters';
        } else if(tabId === 'sections-tab') {
            window.location.hash = '';
        }
    }
    
    // Check hash on load
    window.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash === '#filters') {
            switchTab(null, 'filters-tab');
        }
        
        // Fade out toasts
        setTimeout(() => {
            document.querySelectorAll('.toast-alert').forEach(toast => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            });
        }, 3000);
    });

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

    // Filter Character Counter
    const filterNameInput = document.getElementById('filterNameInput');
    const filterCharCount = document.getElementById('filterCharCount');
    if (filterNameInput && filterCharCount) {
        filterNameInput.addEventListener('input', function () {
            filterCharCount.textContent = this.value.length;
        });
        filterCharCount.textContent = filterNameInput.value.length;
    }

    // Populate Filter Edit Form
    function editFilter(id, name, values) {
        document.getElementById('filterFormTitle').textContent = 'Edit Filter';
        document.getElementById('filterFormAction').value = 'edit_filter';
        document.getElementById('filterFormId').value = id;

        filterNameInput.value = name;
        filterCharCount.textContent = name.length;
        
        document.getElementById('filterValuesInput').value = values;

        const submitBtn = document.getElementById('filterSubmitBtn');
        submitBtn.textContent = 'Update Filter';
        
        document.getElementById('filterCancelBtn').style.display = 'block';
        window.scrollTo(0, 0);
    }

    // Cancel Filter Edit Mode
    function cancelFilterEditMode() {
        document.getElementById('filterFormTitle').textContent = 'Add New Filter';
        document.getElementById('filterFormAction').value = 'add_filter';
        document.getElementById('filterFormId').value = '';

        filterNameInput.value = '';
        filterCharCount.textContent = '0';
        document.getElementById('filterValuesInput').value = '';

        const submitBtn = document.getElementById('filterSubmitBtn');
        submitBtn.textContent = 'Add Filter';
        
        document.getElementById('filterCancelBtn').style.display = 'none';
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

        // Drag and Drop functionality (Generic for all tbodys)
        const tbodies = document.querySelectorAll('tbody');
        let draggedRow = null;
        let dragType = null; // 'section' or 'field'

        tbodies.forEach(tableBody => {
            tableBody.addEventListener('dragstart', function (e) {
                const row = e.target.closest('tr');
                if (row && (row.classList.contains('draggable-row') || row.classList.contains('draggable-field-row'))) {
                    draggedRow = row;
                    dragType = row.classList.contains('draggable-row') ? 'section' : 'field';
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', draggedRow.innerHTML);
                    setTimeout(() => { draggedRow.style.opacity = '0.5'; }, 0);
                }
            });

            tableBody.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                const targetRow = e.target.closest('tr');
                if (targetRow && targetRow !== draggedRow && targetRow.classList.contains(dragType === 'section' ? 'draggable-row' : 'draggable-field-row')) {
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
                    updateOrder(dragType);
                    dragType = null;
                }
            });
        });

        function updateOrder(type) {
            const rowClass = type === 'section' ? '.draggable-row' : '.draggable-field-row';
            const action = type === 'section' ? 'reorder_sections' : 'reorder_fields';
            const successMsg = type === 'section' ? 'Section order updated successfully!' : 'Field order updated successfully!';
            
            const rows = document.querySelectorAll(rowClass);
            const order = Array.from(rows).map(row => row.getAttribute('data-id'));

            const formData = new FormData();
            formData.append('action', action);
            order.forEach((id, index) => {
                formData.append(`order[${index}]`, id);
            });

            fetch('<?php echo URLROOT; ?>/settings/student_form', {
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
                            toast.innerHTML = `<span class="toast-message-bold">Success:</span>${successMsg}`;
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

    // --- Form Fields Builder JS ---
    
    const fieldNameInput = document.getElementById('fieldNameInput');
    const fieldCharCount = document.getElementById('fieldCharCount');
    if (fieldNameInput && fieldCharCount) {
        fieldNameInput.addEventListener('input', function () {
            fieldCharCount.textContent = this.value.length;
        });
        fieldCharCount.textContent = fieldNameInput.value.length;
    }

    function onFieldTypeChange() {
        const type = document.getElementById('fieldTypeSelect').value;
        const configs = document.querySelectorAll('.dynamic-config');
        configs.forEach(el => el.style.display = 'none');
        
        if(type === 'text') {
            document.getElementById('config-placeholder').style.display = 'block';
            document.getElementById('config-charlimit').style.display = 'block';
        } else if(type === 'textarea') {
            document.getElementById('config-charlimit').style.display = 'block';
        } else if(type === 'radio' || type === 'dropdown') {
            document.getElementById('config-values').style.display = 'block';
        } else if(type === 'filter') {
            document.getElementById('config-filter').style.display = 'block';
        } else if(type === 'file') {
            document.getElementById('config-file').style.display = 'block';
        }
    }
    
    // Trigger on load for pre-filled data
    document.addEventListener('DOMContentLoaded', () => {
        if(document.getElementById('fieldTypeSelect') && document.getElementById('fieldTypeSelect').value !== '') {
            onFieldTypeChange();
        }
        
        // Setup initial hash check for form-tab
        if (window.location.hash === '#form') {
            switchTab(null, 'form-tab');
        }
    });

    function editField(field) {
        document.getElementById('fieldFormTitle').textContent = 'Edit Field';
        document.getElementById('fieldFormAction').value = 'edit_field';
        document.getElementById('fieldFormId').value = field.id;

        document.getElementById('fieldSectionId').value = field.section_id;
        document.getElementById('fieldNameInput').value = field.field_name;
        fieldCharCount.textContent = field.field_name.length;
        
        document.getElementById('fieldTypeSelect').value = field.field_type;
        onFieldTypeChange();

        document.getElementById('fieldFilterId').value = field.filter_id || '';
        document.getElementById('fieldPlaceholderText').value = field.placeholder_text || '';
        document.getElementById('fieldValuesInput').value = field.field_values || '';
        document.getElementById('fieldCharLimit').value = field.char_limit || '';

        document.getElementById('fieldIsRequired').checked = (field.is_required == 1);
        document.getElementById('fieldShowOnForm').checked = (field.show_on_form == 1);
        document.getElementById('fieldShowToUser').checked = (field.show_to_user == 1);

        document.getElementById('fieldSubmitBtn').textContent = 'Update Field';
        document.getElementById('fieldCancelBtn').style.display = 'block';
        
        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function cancelFieldEditMode() {
        document.getElementById('fieldFormTitle').textContent = 'Add New Field';
        document.getElementById('fieldFormAction').value = 'add_field';
        document.getElementById('fieldFormId').value = '';

        document.getElementById('fieldSectionId').value = '';
        document.getElementById('fieldNameInput').value = '';
        fieldCharCount.textContent = '0';
        
        document.getElementById('fieldTypeSelect').value = '';
        onFieldTypeChange();

        document.getElementById('fieldFilterId').value = '';
        document.getElementById('fieldPlaceholderText').value = '';
        document.getElementById('fieldValuesInput').value = '';
        document.getElementById('fieldCharLimit').value = '';

        document.getElementById('fieldIsRequired').checked = true;
        document.getElementById('fieldShowOnForm').checked = true;
        document.getElementById('fieldShowToUser').checked = true;

        document.getElementById('fieldSubmitBtn').textContent = 'Add Field';
        document.getElementById('fieldCancelBtn').style.display = 'none';
    }
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>