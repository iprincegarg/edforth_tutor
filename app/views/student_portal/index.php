<?php require_once APPROOT . '/views/inc/front_header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/common.css">

<div class="auth-container" style="margin-top: 150px; min-height: 60vh; display: flex; justify-content: center; align-items: center; background-color: #f4f7f6;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; background: white;">
        <div class="card-header" style="text-align: center; margin-bottom: 20px;">
            <img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth" style="max-height: 40px; margin-bottom: 10px;" />
        <h1 class="card-title" style="font-size: 24px; color: #333;">Student Portal</h1>
            <p class="card-subtitle" style="color: #666; font-size: 14px;">Sign in to your Student account or sign up to become a student.</p>
        </div>

        <form action="<?php echo URLROOT; ?>/student-portal/login" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email" class="form-label" style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Username</label>
                <input type="text" name="email" id="email"
                    class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    value="<?php echo htmlspecialchars($data['email']); ?>" placeholder="Enter your username">
                <?php if (!empty($data['email_err'])): ?>
                    <span class="form-error-text" style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                        <?php echo $data['email_err']; ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password" class="form-label" style="display: block; margin-bottom: 5px; font-weight: bold; color: #555;">Password</label>
                <input type="password" name="password" id="password"
                    class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                    placeholder="••••••••">
                <?php if (!empty($data['password_err'])): ?>
                    <span class="form-error-text" style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                        <?php echo $data['password_err']; ?>
                    </span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; background-color: #007bff; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; margin-bottom: 15px;">Sign In</button>
            
            <div class="text-center" style="margin-top: 10px; padding-top: 15px; border-top: 1px solid #eee; text-align: center;">
                <p style="color: #666; font-size: 14px; margin-bottom: 10px;">Don't have an account yet?</p>
                <a href="<?php echo URLROOT; ?>/register-as-student" class="btn btn-outline-primary" style="width: 100%; text-decoration: none; display: inline-block; text-align: center; padding: 10px; border: 1px solid #007bff; color: #007bff; border-radius: 4px; box-sizing: border-box;">Sign Up as a Student</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/front_footer.php'; ?>
