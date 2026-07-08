<?php require_once APPROOT . '/views/inc/dash_header.php'; ?>

<div class="card" style="margin: 20px;">
    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 15px;">
        <h3 class="card-title" style="margin: 0;">Front CMS Editor</h3>
    </div>
    <div class="card-body" style="padding: 20px;">
        <?php if(!empty($data['success_msg'])): ?>
            <div class="alert alert-success" style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 4px;">
                <?php echo $data['success_msg']; ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['error_msg'])): ?>
            <div class="alert alert-danger" style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 15px; border-radius: 4px;">
                <?php echo $data['error_msg']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/settings/front_cms" method="POST">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editor" style="display: block; margin-bottom: 10px; font-weight: bold;">Homepage Content (HTML)</label>
                <textarea name="content" id="editor" rows="20" class="form-control" style="width: 100%; height: 500px;"><?php echo htmlspecialchars($data['content'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Content</button>
        </form>
    </div>
</div>

<!-- Include CKEditor 5 from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>

<?php require_once APPROOT . '/views/inc/dash_footer.php'; ?>
