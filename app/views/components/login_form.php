<div class="card">
    <div class="card-header">
        <img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth" style="max-height: 40px; margin-bottom: 10px;" />
        <h1 class="card-title">EdForth Tutor</h1>
        <p class="card-subtitle">Sign in to your EdForth Admin account</p>
    </div>

    <form action="<?php echo URLROOT; ?>/<?php echo strtolower($data['role']); ?>/login" method="POST">
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email"
                class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                value="<?php echo $data['email']; ?>" placeholder="admin@enterprise.com">
            <?php if (!empty($data['email_err'])): ?>
                <span class="form-error-text">
                    <?php echo $data['email_err']; ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password"
                class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"
                placeholder="••••••••">
            <?php if (!empty($data['password_err'])): ?>
                <span class="form-error-text">
                    <?php echo $data['password_err']; ?>
                </span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
</div>