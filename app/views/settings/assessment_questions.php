<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Questions Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1400px; margin: 0 auto; background: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; position: relative; }
        h2 { margin-top: 0; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; color: #333; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #007bff; border-radius: 4px; }
        .pagination a:hover { background-color: #f1f5f9; }
        .pagination .active { background-color: #007bff; color: #fff; border-color: #007bff; }
        .levels-badge { font-size: 0.85em; background: #e2e8f0; padding: 4px 8px; border-radius: 4px; color: #475569; display: inline-block; line-height: 1.4; }
        
        /* Buttons */
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 13px; }
        .btn-edit { background-color: #0ea5e9; color: white; }
        .btn-edit:hover { background-color: #0284c7; }
        .btn-delete { background-color: #ef4444; color: white; }
        .btn-delete:hover { background-color: #dc2626; }
        
        /* Alerts */
        .alert { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal { background: #fff; padding: 25px; border-radius: 8px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .modal h3 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #475569; font-size: 14px; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
        .modal-footer { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-cancel { background: #f1f5f9; color: #475569; }
        .btn-save { background: #10b981; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assessment Questions Report</h2>
        
        <?php if (!empty($data['success_msg'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($data['success_msg']); ?></div>
        <?php endif; ?>
        <?php if (!empty($data['error_msg'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($data['error_msg']); ?></div>
        <?php endif; ?>

        <?php if(empty($data['questions'])): ?>
            <p>No questions found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th style="width: 15%;">Hierarchy / Levels</th>
                        <th style="width: 25%;">Question</th>
                        <th>Option A</th>
                        <th>Option B</th>
                        <th>Option C</th>
                        <th>Option D</th>
                        <th>Answer</th>
                        <th>Marks</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['questions'] as $q): ?>
                        <tr>
                            <td><?php echo $q->id; ?></td>
                            <td><span class="levels-badge"><?php echo $q->hierarchy; ?></span></td>
                            <td><?php echo htmlspecialchars($q->question); ?></td>
                            <td><?php echo htmlspecialchars($q->option_a); ?></td>
                            <td><?php echo htmlspecialchars($q->option_b); ?></td>
                            <td><?php echo htmlspecialchars($q->option_c); ?></td>
                            <td><?php echo htmlspecialchars($q->option_d); ?></td>
                            <td><strong><?php echo htmlspecialchars($q->correct_answer); ?></strong></td>
                            <td><?php echo htmlspecialchars($q->marks); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-direction: column;">
                                    <?php 
                                        $qData = htmlspecialchars(json_encode([
                                            'id' => $q->id,
                                            'question' => $q->question,
                                            'option_a' => $q->option_a,
                                            'option_b' => $q->option_b,
                                            'option_c' => $q->option_c,
                                            'option_d' => $q->option_d,
                                            'correct_answer' => $q->correct_answer,
                                            'explanation' => $q->explanation,
                                            'marks' => $q->marks
                                        ]), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <button class="btn btn-edit" onclick="openEditModal(<?php echo $qData; ?>)">Edit</button>
                                    
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="question_id" value="<?php echo $q->id; ?>">
                                        <button type="submit" class="btn btn-delete" style="width: 100%;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if($data['totalPages'] > 1): ?>
                <div class="pagination">
                    <?php if($data['page'] > 1): ?>
                        <a href="?page=<?php echo $data['page'] - 1; ?>">&laquo; Prev</a>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $data['page'] - 2);
                    $end = min($data['totalPages'], $data['page'] + 2);
                    for($i = $start; $i <= $end; $i++): ?>
                        <?php if($i == $data['page']): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if($data['page'] < $data['totalPages']): ?>
                        <a href="?page=<?php echo $data['page'] + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal">
            <h3>Edit Question</h3>
            <form action="" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="question_id" id="edit_id">
                
                <div class="form-group">
                    <label>Question</label>
                    <textarea name="question" id="edit_question" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Option A</label>
                    <input type="text" name="option_a" id="edit_option_a" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Option B</label>
                    <input type="text" name="option_b" id="edit_option_b" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Option C</label>
                    <input type="text" name="option_c" id="edit_option_c" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Option D</label>
                    <input type="text" name="option_d" id="edit_option_d" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Correct Answer (e.g. option_a)</label>
                    <input type="text" name="correct_answer" id="edit_correct_answer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Explanation</label>
                    <textarea name="explanation" id="edit_explanation" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Marks</label>
                    <input type="number" name="marks" id="edit_marks" class="form-control" value="1" required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_question').value = data.question;
            document.getElementById('edit_option_a').value = data.option_a;
            document.getElementById('edit_option_b').value = data.option_b;
            document.getElementById('edit_option_c').value = data.option_c;
            document.getElementById('edit_option_d').value = data.option_d;
            document.getElementById('edit_correct_answer').value = data.correct_answer;
            document.getElementById('edit_explanation').value = data.explanation || '';
            document.getElementById('edit_marks').value = data.marks;
            
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if(e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
