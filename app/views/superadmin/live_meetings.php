<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 0;">Classes</h3>
                <button type="button" class="btn btn-primary" style="background-color:#3b82f6; color:white; padding: 6px 12px; font-size: 0.85rem; border:none; border-radius:4px; font-weight:600; cursor:pointer; width: auto; min-width: 0;" onclick="document.getElementById('scheduleModal').style.display='flex'">Schedule New Class</button>
            </div>
            <div class="card-body" style="padding: 20px;">
                
                <?php if (empty($data['meetings'])): ?>
                    <div class="alert alert-info">
                        No meetings scheduled yet.
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 12px; font-weight: 600; color: #475569;">ID</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Tutor</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Topic</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Type</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Scheduled For</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['meetings'] as $meeting): ?>
                                <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px;">#<?php echo $meeting->id; ?></td>
                                    <td style="padding: 12px; font-weight: 600; color:#0f172a;"><?php echo htmlspecialchars($meeting->tutor_name ?? 'Unknown'); ?></td>
                                    <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($meeting->topic); ?></td>
                                    <td style="padding: 12px;">
                                        <?php if ($meeting->type === 'online'): ?>
                                            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Online</span>
                                        <?php else: ?>
                                            <span style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Onsite</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px; color: #64748b; font-size: 0.9rem;"><?php echo date('M d, Y h:i A', strtotime($meeting->scheduled_at)); ?></td>
                                    <td style="padding: 12px;">
                                        <?php if ($meeting->status === 'completed'): ?>
                                            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Completed</span>
                                        <?php elseif ($meeting->status === 'cancelled'): ?>
                                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Cancelled</span>
                                        <?php else: ?>
                                            <span style="background: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Scheduled</span>
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

<!-- Schedule Class Modal -->
<div id="scheduleModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; width:500px; max-width:90%;">
        <h4 style="margin-top:0; display:flex; justify-content:space-between; align-items:center; color:#0f172a; font-size:1.25rem;">
            Schedule New Class
            <button type="button" onclick="document.getElementById('scheduleModal').style.display='none'" style="border:none; background:transparent; font-size:1.5rem; cursor:pointer; color:#64748b;">&times;</button>
        </h4>
        
        <form action="<?php echo URLROOT; ?>/superadmin/classes" method="POST">
            <div class="form-group tutor-form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Select Tutor <span style="color:#ef4444">*</span></label>
                <select name="tutor_id" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="">-- Select a Tutor --</option>
                    <?php foreach ($data['tutors'] as $tutor): ?>
                        <option value="<?php echo $tutor->id; ?>"><?php echo htmlspecialchars($tutor->username); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group tutor-form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Topic <span style="color:#ef4444">*</span></label>
                <input type="text" name="topic" class="form-control" required placeholder="e.g. Advanced Mathematics" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div class="form-group tutor-form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Type <span style="color:#ef4444">*</span></label>
                <select name="type" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="online">Online (Jitsi)</option>
                    <option value="onsite">Onsite</option>
                </select>
            </div>
            
            <div class="form-group tutor-form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Date and Time <span style="color:#ef4444">*</span></label>
                <input type="datetime-local" name="scheduled_at" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <button type="button" class="btn tutor-btn-cancel" onclick="document.getElementById('scheduleModal').style.display='none'" style="padding: 8px 16px; border: 1px solid #cbd5e1; background: #fff; color: #475569; border-radius: 4px; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color:#3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">Schedule</button>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
