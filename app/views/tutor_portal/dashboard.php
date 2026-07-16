<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 0;">Welcome, <?php echo htmlspecialchars($data['username']); ?>!</h3>
                <?php if (!empty($data['submission'])): ?>
                    <span style="padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; background: #d1fae5; color: #065f46;">
                        STATUS: <?php echo htmlspecialchars($data['submission']->status); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body" style="padding: 20px;">
                <p style="margin-bottom: 20px;">Welcome to your Tutor Dashboard. Here you will be able to manage your profile, view your assigned students, and track your schedule.</p>
                
                <?php if (!empty($data['submission'])): ?>
                    <h4 style="margin-bottom: 15px; color: #334155; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">My Profile Details</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                        <?php 
                        $formData = json_decode($data['submission']->form_data, true);
                        $fieldNamesMap = [];
                        if(!empty($data['fields'])) {
                            foreach($data['fields'] as $f) {
                                $fieldNamesMap['field_' . $f->id] = $f->field_name;
                            }
                        }

                        if ($formData && is_array($formData)) {
                            foreach ($formData as $key => $val) {
                                $displayName = $fieldNamesMap[$key] ?? str_replace('field_', 'Field ', $key);
                                
                                if($val && is_string($val) && strpos($val, 'uploads/') === 0) {
                                    $valHtml = '<a href="' . URLROOT . '/../' . htmlspecialchars($val) . '" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #e0f2fe; color: #0284c7; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: background 0.2s;" onmouseover="this.style.background=\'#bae6fd\'" onmouseout="this.style.background=\'#e0f2fe\'">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        View File
                                       </a>';
                                } elseif (is_array($val)) {
                                    $safeVal = htmlspecialchars(implode(', ', $val));
                                    $valHtml = $safeVal ? $safeVal : '<span style="color:#94a3b8; font-style:italic;">N/A</span>';
                                } else {
                                    $safeVal = htmlspecialchars((string)$val);
                                    $valHtml = $safeVal ? $safeVal : '<span style="color:#94a3b8; font-style:italic;">N/A</span>';
                                }
                                
                                echo '
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 6px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px -1px rgba(0,0,0,0.05)\';" onmouseout="this.style.transform=\'none\'; this.style.boxShadow=\'0 1px 2px rgba(0,0,0,0.02)\';">
                                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #64748b;">' . htmlspecialchars($displayName) . '</span>
                                    <div style="font-size: 0.95rem; color: #0f172a; word-break: break-word; line-height: 1.5;">' . $valHtml . '</div>
                                </div>';
                            }
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <strong>Profile Data Missing!</strong> We could not locate your application submission data.
                    </div>
                <?php endif; ?>
                
                <div class="alert alert-info mt-4">
                    <strong>Note:</strong> More features for the Tutor Dashboard will be added soon!
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
