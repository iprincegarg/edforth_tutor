<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="margin: 0;">Ticket #<?php echo htmlspecialchars($data['ticket']->id); ?></h3>
                <a href="<?php echo URLROOT; ?>/tutor-dashboard/tickets" style="color: #64748b; text-decoration: none; font-weight: 500;">&larr; Back to Tickets</a>
            </div>
            <div class="card-body" style="padding: 20px;">
                
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="font-weight: 600; font-size: 1.1rem; color: #0f172a;"><?php echo htmlspecialchars($data['ticket']->subject); ?></span>
                        <span>
                            <?php if ($data['ticket']->status === 'closed'): ?>
                                <span style="background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Closed</span>
                            <?php else: ?>
                                <span style="background: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Pending</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div style="font-size: 0.95rem; color: #334155; line-height: 1.5; white-space: pre-wrap;"><?php echo htmlspecialchars($data['ticket']->message); ?></div>
                    <div style="margin-top: 10px; font-size: 0.8rem; color: #94a3b8;">Created at: <?php echo date('M d, Y h:i A', strtotime($data['ticket']->created_at)); ?></div>
                </div>

                <h4 style="margin-bottom: 15px; color: #334155; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">Replies</h4>
                
                <?php 
                $replies = json_decode($data['ticket']->reply_json ?? '[]', true) ?? [];
                if (empty($replies)): ?>
                    <p style="color: #64748b; font-style: italic; margin-bottom: 20px;">No replies yet.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px;">
                        <?php foreach ($replies as $reply): ?>
                            <?php $isMe = ($reply['userID'] == $_SESSION['user_id']); ?>
                            <div style="background: <?php echo $isMe ? '#e0f2fe' : '#f1f5f9'; ?>; padding: 12px 15px; border-radius: 6px; border: 1px solid <?php echo $isMe ? '#bae6fd' : '#e2e8f0'; ?>;">
                                <div style="font-size: 0.85rem; font-weight: 600; color: <?php echo $isMe ? '#0284c7' : '#475569'; ?>; margin-bottom: 5px;">
                                    <?php echo $isMe ? 'Me' : 'Support Agent'; ?> <span style="font-weight: 400; color: #94a3b8; font-size: 0.75rem; margin-left: 10px;"><?php echo date('M d, Y h:i A', strtotime($reply['timestamp'])); ?></span>
                                </div>
                                <div style="font-size: 0.95rem; color: #334155; white-space: pre-wrap; line-height: 1.4;"><?php echo htmlspecialchars($reply['message']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($data['ticket']->status !== 'closed'): ?>
                    <form action="<?php echo URLROOT; ?>/tutor-dashboard/viewTicket/<?php echo $data['ticket']->id; ?>" method="POST" style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                        <div class="form-group tutor-form-group">
                            <label class="form-label" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Add a Reply</label>
                            <textarea name="reply_message" class="form-control" required rows="3" placeholder="Type your reply here..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; resize: vertical;"></textarea>
                        </div>
                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="btn btn-primary" style="background-color:#3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; width: auto; min-width: 0;">Send Reply</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info" style="margin-top: 20px; text-align: center;">
                        This ticket is closed. You cannot add new replies.
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
