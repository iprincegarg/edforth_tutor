<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 0;">My Tickets</h3>
                <button type="button" class="btn btn-primary" style="background-color:#3b82f6; color:white; padding: 4px 8px; font-size: 0.8rem; border:none; border-radius:4px; font-weight:500; cursor:pointer; width: auto; min-width: 0;" onclick="document.getElementById('raiseTicketModal').style.display='flex'">Raise Ticket</button>
            </div>
            <div class="card-body" style="padding: 20px;">
                
                <?php if (empty($data['tickets'])): ?>
                    <div class="alert alert-info">
                        You have not raised any tickets yet.
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 12px; font-weight: 600; color: #475569;">ID</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Subject</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Status</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Created At</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['tickets'] as $ticket): ?>
                                <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px;">#<?php echo $ticket->id; ?></td>
                                    <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($ticket->subject); ?></td>
                                    <td style="padding: 12px;">
                                        <?php if ($ticket->status === 'closed'): ?>
                                            <span style="background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Closed</span>
                                        <?php else: ?>
                                            <span style="background: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px; color: #64748b; font-size: 0.9rem;"><?php echo date('M d, Y h:i A', strtotime($ticket->created_at)); ?></td>
                                    <td style="padding: 12px;">
                                        <a href="<?php echo URLROOT; ?>/student-dashboard/viewTicket/<?php echo $ticket->id; ?>" style="color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 0.9rem;">View</a>
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

<!-- Raise Ticket Modal -->
<div id="raiseTicketModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; width:500px; max-width:90%;">
        <h4 style="margin-top:0; display:flex; justify-content:space-between; align-items:center; color:#0f172a; font-size:1.25rem;">
            Raise a Ticket
            <button type="button" onclick="document.getElementById('raiseTicketModal').style.display='none'" style="border:none; background:transparent; font-size:1.5rem; cursor:pointer; color:#64748b;">&times;</button>
        </h4>
        <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom: 20px;">Describe your issue below. We will get back to you soon.</p>
        
        <form action="<?php echo URLROOT; ?>/student-dashboard/raiseTicket" method="POST">
            <div class="form-group student-form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Subject <span style="color:#ef4444">*</span></label>
                <input type="text" name="subject" class="form-control" maxlength="100" required placeholder="Brief subject" oninput="document.getElementById('subjectCount').innerText = this.value.length + '/100'" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                <div style="text-align: right; font-size: 0.75rem; color: #64748b; margin-top: 4px;" id="subjectCount">0/100</div>
            </div>
            
            <div class="form-group student-form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Message <span style="color:#ef4444">*</span></label>
                <textarea name="message" class="form-control" maxlength="500" required rows="5" placeholder="Describe your issue in detail..." oninput="document.getElementById('messageCount').innerText = this.value.length + '/500'" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; resize: vertical;"></textarea>
                <div style="text-align: right; font-size: 0.75rem; color: #64748b; margin-top: 4px;" id="messageCount">0/500</div>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <button type="button" class="btn student-btn-cancel" onclick="document.getElementById('raiseTicketModal').style.display='none'" style="padding: 8px 16px; border: 1px solid #cbd5e1; background: #fff; color: #475569; border-radius: 4px; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color:#3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
