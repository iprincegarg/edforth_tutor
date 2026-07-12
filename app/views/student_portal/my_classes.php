<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 100%;">
            <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px;">
                <h3 class="card-title" style="margin: 0;">My Classes</h3>
            </div>
            
            <div style="padding: 15px 20px 0 20px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px;">
                <button id="tab-online" onclick="switchTab('online')" style="padding: 10px 20px; border: none; background: transparent; font-weight: 600; font-size: 1rem; color: #3b82f6; border-bottom: 2px solid #3b82f6; cursor: pointer;">
                    Online
                </button>
                <button id="tab-onsite" onclick="switchTab('onsite')" style="padding: 10px 20px; border: none; background: transparent; font-weight: 500; font-size: 1rem; color: #64748b; border-bottom: 2px solid transparent; cursor: pointer;">
                    Onsite
                </button>
            </div>

            <div class="card-body" style="padding: 20px;">
                
                <!-- Online Tab Content -->
                <div id="content-online" style="display: block;">
                    <?php if (empty($data['online_classes'])): ?>
                        <div class="alert alert-info">
                            You have no online classes scheduled.
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            <?php foreach ($data['online_classes'] as $class): ?>
                                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <h4 style="margin: 0 0 10px 0; color: #0f172a; font-size: 1.1rem;"><?php echo htmlspecialchars($class->topic); ?></h4>
                                    <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 15px;">
                                        <div style="margin-bottom: 5px;"><strong>Scheduled:</strong> <?php echo date('M d, Y h:i A', strtotime($class->scheduled_at)); ?></div>
                                        <div><strong>Status:</strong> <span style="text-transform: capitalize; color: <?php echo $class->status === 'completed' ? '#059669' : '#0f172a'; ?>"><?php echo $class->status; ?></span></div>
                                    </div>
                                    
                                    <?php if ($class->status !== 'cancelled'): ?>
                                        <a href="https://meet.jit.si/<?php echo urlencode($class->room_name); ?>" target="_blank" class="btn btn-primary" style="display: inline-block; background-color:#3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; text-decoration: none; width: 100%; text-align: center;">Join Class</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Onsite Tab Content -->
                <div id="content-onsite" style="display: none;">
                    <?php if (empty($data['onsite_classes'])): ?>
                        <div class="alert alert-info">
                            You have no onsite classes scheduled.
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            <?php foreach ($data['onsite_classes'] as $class): ?>
                                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <h4 style="margin: 0 0 10px 0; color: #0f172a; font-size: 1.1rem;"><?php echo htmlspecialchars($class->topic); ?></h4>
                                    <div style="color: #64748b; font-size: 0.9rem;">
                                        <div style="margin-bottom: 5px;"><strong>Scheduled:</strong> <?php echo date('M d, Y h:i A', strtotime($class->scheduled_at)); ?></div>
                                        <div><strong>Status:</strong> <span style="text-transform: capitalize; color: <?php echo $class->status === 'completed' ? '#059669' : '#0f172a'; ?>"><?php echo $class->status; ?></span></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    document.getElementById('content-online').style.display = 'none';
    document.getElementById('content-onsite').style.display = 'none';
    
    document.getElementById('tab-online').style.fontWeight = '500';
    document.getElementById('tab-online').style.color = '#64748b';
    document.getElementById('tab-online').style.borderBottom = '2px solid transparent';
    
    document.getElementById('tab-onsite').style.fontWeight = '500';
    document.getElementById('tab-onsite').style.color = '#64748b';
    document.getElementById('tab-onsite').style.borderBottom = '2px solid transparent';
    
    document.getElementById('content-' + tabName).style.display = 'block';
    document.getElementById('tab-' + tabName).style.fontWeight = '600';
    document.getElementById('tab-' + tabName).style.color = '#3b82f6';
    document.getElementById('tab-' + tabName).style.borderBottom = '2px solid #3b82f6';
}
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
