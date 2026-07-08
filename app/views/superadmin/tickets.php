<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px;">
                <h3 class="card-title" style="margin: 0;">Support Tickets</h3>
            </div>
            <div class="card-body" style="padding: 20px;">
                
                <?php if (empty($data['tickets'])): ?>
                    <div class="alert alert-info">
                        There are no support tickets.
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 12px; font-weight: 600; color: #475569;">ID</th>
                                <th style="padding: 12px; font-weight: 600; color: #475569;">Tutor Username</th>
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
                                    <td style="padding: 12px; font-weight: 600; color:#0f172a;"><?php echo htmlspecialchars($ticket->username ?? 'Unknown'); ?></td>
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
                                        <a href="<?php echo URLROOT; ?>/superadmin/viewTicket/<?php echo $ticket->id; ?>" style="color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 0.9rem;">View & Reply</a>
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

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
