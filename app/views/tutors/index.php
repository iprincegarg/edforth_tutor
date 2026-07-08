<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>
<?php 
$hasActiveFilters = !empty($data['activeFilters']);
$hasSearch = !empty($data['search']);
$hasAnyFilter = $hasActiveFilters || $hasSearch;
?>
<div class="tutor-form-header" style="margin-bottom: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin:0; font-size:1.5rem; color:#1e293b;">Tutor Applications</h2>
        
        <!-- Search & Filter Form -->
        <form action="<?php echo URLROOT; ?>/tutors" method="GET" id="tutorFilterForm" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
            <input type="text" name="search" class="form-control" placeholder="Search by ID or Name..." value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>" style="width: 220px; padding: 0.4rem 0.75rem;">
            
            <?php if (!empty($data['filterFields'])): ?>
                <?php foreach ($data['filterFields'] as $ff): ?>
                    <?php 
                    $opts = array_map('trim', explode(',', $ff->filterValues));
                    $selected = $data['activeFilters'][$ff->id] ?? '';
                    ?>
                    <select name="filter_field_<?php echo $ff->id; ?>" class="form-control" style="width: auto; padding: 0.4rem 0.75rem; min-width: 140px;">
                        <option value="">-- <?php echo htmlspecialchars($ff->field_name); ?> --</option>
                        <?php foreach ($opts as $opt): ?>
                            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo $selected === $opt ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary" style="width: auto; padding: 0.4rem 1rem;">Filter</button>
            <?php if ($hasAnyFilter): ?>
                <a href="<?php echo URLROOT; ?>/tutors" class="btn tutor-btn-cancel" style="display:inline-flex; width:auto; text-decoration:none; padding: 0.4rem 1rem;">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="toast-container">
    <?php if (!empty($data['success_msg'])): ?>
        <div class="toast-alert toast-success">
            <span class="toast-message-bold">Success:</span><?php echo $data['success_msg']; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($data['field_err'])): ?>
        <div class="toast-alert toast-error">
            <span class="toast-message-bold">Error:</span><?php echo $data['field_err']; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
$firstField = !empty($data['fields']) ? $data['fields'][0] : null;
$firstFieldKey = $firstField ? 'field_' . $firstField->id : null;
$firstFieldName = $firstField ? $firstField->field_name : 'Primary Info';

// Helper function to build pagination URLs
function buildPaginationUrl($t_page, $s_page, $search) {
    $url = URLROOT . '/tutors?t_page=' . $t_page . '&s_page=' . $s_page;
    if (!empty($search)) {
        $url .= '&search=' . urlencode($search);
    }
    return $url;
}
?>

<div class="tutor-form-container" style="display:block; margin-top:1.5rem;">
    
    <ul class="nav-pills tutor-form-tabs" id="tutorTabs" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active" onclick="switchTab(event, 'tutors-tab')">Tutors (<?php echo $data['totalTutors']; ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="switchTab(event, 'submissions-tab')">Submissions (<?php echo $data['totalPending']; ?>)</a>
        </li>
    </ul>

    <div class="tab-content tutor-form-content" style="padding-top: 1.5rem;">
        
        <!-- Tutors Tab (Approved/Rejected) -->
        <div id="tutors-tab" class="tab-pane active">
            <div class="card tutor-card" style="width: 100%;">
                <div class="tutor-card-body" style="overflow-x: auto;">
                    <?php if (empty($data['tutorSubmissions'])): ?>
                        <div class="tutor-sections-empty">
                            <p class="tutor-sections-empty-text">No tutors found.</p>
                        </div>
                    <?php else: ?>
                        <table class="tutor-sections-table" style="width: 100%; min-width: 800px;">
                            <thead class="tutor-sections-thead">
                                <tr>
                                    <th class="tutor-sections-th" style="width: 10%;">ID</th>
                                    <th class="tutor-sections-th" style="width: 20%;"><?php echo htmlspecialchars($firstFieldName); ?></th>
                                    <th class="tutor-sections-th" style="width: 20%;">Status</th>
                                    <th class="tutor-sections-th" style="width: 20%;">Submitted At</th>
                                    <th class="tutor-sections-th-actions" style="width: 30%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['tutorSubmissions'] as $sub): 
                                    $fData = json_decode($sub->form_data, true);
                                    $firstVal = ($firstFieldKey && isset($fData[$firstFieldKey])) ? $fData[$firstFieldKey] : 'N/A';
                                ?>
                                    <tr class="tutor-sections-tr">
                                        <td class="tutor-sections-td">#<?php echo htmlspecialchars($sub->id); ?></td>
                                        <td class="tutor-sections-td" style="font-weight: 600; color: var(--primary-color);"><?php echo htmlspecialchars($firstVal); ?></td>
                                        <td class="tutor-sections-td">
                                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                                                <?php 
                                                if($sub->status === 'approved') echo 'background: #d1fae5; color: #065f46;';
                                                elseif($sub->status === 'rejected') echo 'background: #fee2e2; color: #991b1b;';
                                                else echo 'background: #fef3c7; color: #92400e;'; 
                                                ?>
                                            ">
                                                <?php echo htmlspecialchars($sub->status); ?>
                                            </span>
                                        </td>
                                        <td class="tutor-sections-td" style="font-size: 0.9rem; color: var(--text-muted);"><?php echo htmlspecialchars(date('M j, Y h:i A', strtotime($sub->created_at))); ?></td>
                                        <td class="tutor-sections-td-actions" style="justify-content: flex-start; gap: 8px;">
                                            <button type="button" class="action-btn-edit" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="View Data" onclick='viewSubmissionData(<?php echo htmlspecialchars(json_encode($sub->form_data), ENT_QUOTES, "UTF-8"); ?>)'>
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination for Tutors Tab -->
                        <?php if($data['t_totalPages'] > 1): ?>
                        <div style="padding: 1rem; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Showing page <?php echo $data['t_page']; ?> of <?php echo $data['t_totalPages']; ?></span>
                            <div style="display: flex; gap: 5px;">
                                <?php if($data['t_page'] > 1): ?>
                                    <a href="<?php echo buildPaginationUrl($data['t_page'] - 1, $data['s_page'], $data['search']); ?>#tutors" class="btn btn-primary" style="width: auto; padding: 4px 10px; font-size: 0.8rem;">Prev</a>
                                <?php endif; ?>
                                
                                <?php if($data['t_page'] < $data['t_totalPages']): ?>
                                    <a href="<?php echo buildPaginationUrl($data['t_page'] + 1, $data['s_page'], $data['search']); ?>#tutors" class="btn btn-primary" style="width: auto; padding: 4px 10px; font-size: 0.8rem;">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Submissions Tab (Pending) -->
        <div id="submissions-tab" class="tab-pane">
            <div class="card tutor-card" style="width: 100%;">
                <div class="tutor-card-body" style="overflow-x: auto;">
                    <?php if (empty($data['pendingSubmissions'])): ?>
                        <div class="tutor-sections-empty">
                            <p class="tutor-sections-empty-text">No pending submissions found.</p>
                        </div>
                    <?php else: ?>
                        <table class="tutor-sections-table" style="width: 100%; min-width: 800px;">
                            <thead class="tutor-sections-thead">
                                <tr>
                                    <th class="tutor-sections-th" style="width: 10%;">ID</th>
                                    <th class="tutor-sections-th" style="width: 20%;"><?php echo htmlspecialchars($firstFieldName); ?></th>
                                    <th class="tutor-sections-th" style="width: 20%;">Status</th>
                                    <th class="tutor-sections-th" style="width: 20%;">Submitted At</th>
                                    <th class="tutor-sections-th-actions" style="width: 30%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['pendingSubmissions'] as $sub): 
                                    $fData = json_decode($sub->form_data, true);
                                    $firstVal = ($firstFieldKey && isset($fData[$firstFieldKey])) ? $fData[$firstFieldKey] : 'N/A';
                                ?>
                                    <tr class="tutor-sections-tr">
                                        <td class="tutor-sections-td">#<?php echo htmlspecialchars($sub->id); ?></td>
                                        <td class="tutor-sections-td" style="font-weight: 600; color: var(--primary-color);"><?php echo htmlspecialchars($firstVal); ?></td>
                                        <td class="tutor-sections-td">
                                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; background: #fef3c7; color: #92400e;">
                                                <?php echo htmlspecialchars($sub->status); ?>
                                            </span>
                                        </td>
                                        <td class="tutor-sections-td" style="font-size: 0.9rem; color: var(--text-muted);"><?php echo htmlspecialchars(date('M j, Y h:i A', strtotime($sub->created_at))); ?></td>
                                        <td class="tutor-sections-td-actions" style="justify-content: flex-start; gap: 8px;">
                                            <button type="button" class="action-btn-edit" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="View Data" onclick='viewSubmissionData(<?php echo htmlspecialchars(json_encode($sub->form_data), ENT_QUOTES, "UTF-8"); ?>)'>
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            
                                            <button type="button" class="action-btn-edit" style="color: #10b981;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Approve" onclick="openApproveModal(<?php echo $sub->id; ?>)">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            
                                            <form action="<?php echo URLROOT; ?>/tutors/reject_submission" method="POST" style="display:inline;" class="action-form-inline" onsubmit="return confirm('Are you sure you want to reject this submission?');">
                                                <input type="hidden" name="submission_id" value="<?php echo $sub->id; ?>">
                                                <button type="submit" class="action-btn-delete" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Reject">
                                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination for Submissions Tab -->
                        <?php if($data['s_totalPages'] > 1): ?>
                        <div style="padding: 1rem; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Showing page <?php echo $data['s_page']; ?> of <?php echo $data['s_totalPages']; ?></span>
                            <div style="display: flex; gap: 5px;">
                                <?php if($data['s_page'] > 1): ?>
                                    <a href="<?php echo buildPaginationUrl($data['t_page'], $data['s_page'] - 1, $data['search']); ?>#submissions" class="btn btn-primary" style="width: auto; padding: 4px 10px; font-size: 0.8rem;">Prev</a>
                                <?php endif; ?>
                                
                                <?php if($data['s_page'] < $data['s_totalPages']): ?>
                                    <a href="<?php echo buildPaginationUrl($data['t_page'], $data['s_page'] + 1, $data['search']); ?>#submissions" class="btn btn-primary" style="width: auto; padding: 4px 10px; font-size: 0.8rem;">Next</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; width:400px; max-width:90%;">
        <h4 style="margin-top:0;">Approve Tutor</h4>
        <p style="font-size:0.9rem; color:var(--text-muted);">Please create account credentials for this tutor.</p>
        <form action="<?php echo URLROOT; ?>/tutors/approve_submission" method="POST">
            <input type="hidden" name="submission_id" id="approveSubmissionId" value="">
            <div class="form-group tutor-form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group tutor-form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" class="btn tutor-btn-cancel" onclick="document.getElementById('approveModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-success" style="background-color:#10b981; color: white;">Create User & Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- View Data Modal -->
<div id="viewDataModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; width:600px; max-width:90%; max-height:80vh; overflow-y:auto;">
        <h4 style="margin-top:0; display:flex; justify-content:space-between;">Submission Data
            <button type="button" onclick="document.getElementById('viewDataModal').style.display='none'" style="border:none; background:transparent; font-size:1.2rem; cursor:pointer;">&times;</button>
        </h4>
        <div id="viewDataContent" style="font-size:0.9rem; color:#334155; margin-top:1rem;"></div>
    </div>
</div>

<script>
    const fieldNamesMap = {
        <?php 
        if(!empty($data['fields'])) {
            foreach($data['fields'] as $f) {
                echo '"field_' . $f->id . '": "' . addslashes(htmlspecialchars($f->field_name)) . '",';
            }
        }
        ?>
    };

    function switchTab(event, tabId) {
        if(event) event.preventDefault();
        
        const tabs = document.querySelectorAll('.tutor-form-tabs .nav-link');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        if(event) {
            event.target.classList.add('active');
        } else {
            const activeTabLink = document.querySelector(`.tutor-form-tabs .nav-link[onclick*="${tabId}"]`);
            if (activeTabLink) activeTabLink.classList.add('active');
        }
        
        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(pane => pane.classList.remove('active'));
        
        const activePane = document.getElementById(tabId);
        if(activePane) activePane.classList.add('active');

        // Update URL hash without scroll
        const hash = tabId === 'submissions-tab' ? 'submissions' : 'tutors';
        history.replaceState(null, null, '#' + hash);
    }

    function openApproveModal(id) {
        document.getElementById('approveSubmissionId').value = id;
        document.getElementById('approveModal').style.display = 'flex';
    }

    function viewSubmissionData(jsonStr) {
        let data = typeof jsonStr === 'string' ? JSON.parse(jsonStr) : jsonStr;
        let html = '<table class="tutor-sections-table" style="width:100%; border:1px solid #e2e8f0; border-collapse:collapse;">';
        for(let key in data) {
            let val = data[key];
            if(val && typeof val === 'string' && val.startsWith('uploads/')) {
                val = `<a href="<?php echo URLROOT; ?>/../${val}" target="_blank" style="color:var(--primary); text-decoration:underline;">View File</a>`;
            } else {
                val = val ? val.toString().replace(/</g, '&lt;').replace(/>/g, '&gt;') : 'N/A';
            }
            
            let displayName = fieldNamesMap[key] || key.replace('field_', 'Field ');
            
            html += `<tr><th style="padding:8px; border:1px solid #e2e8f0; text-align:left; background:#f8fafc; width:40%;">${displayName}</th><td style="padding:8px; border:1px solid #e2e8f0;">${val}</td></tr>`;
        }
        html += '</table>';
        document.getElementById('viewDataContent').innerHTML = html;
        document.getElementById('viewDataModal').style.display = 'flex';
    }

    // Auto-dismiss Toasts and Initial Tab Setup
    document.addEventListener('DOMContentLoaded', function () {
        // Setup initial hash check
        if (window.location.hash === '#submissions') {
            switchTab(null, 'submissions-tab');
        }

        const toasts = document.querySelectorAll('.toast-alert');
        if (toasts.length > 0) {
            setTimeout(() => {
                toasts.forEach(toast => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 500);
                });
            }, 3000);
        }
    });
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
