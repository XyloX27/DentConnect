<?php
// expects $role to be set (e.g., 'patient', 'doctor', 'staff', 'admin')
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4><?php echo ucfirst($role); ?> Login</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="../login_process.php">
                    <input type="hidden" name="role" value="<?php echo $role; ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <?php if ($role == 'patient'): ?>
                        <a href="../register.php" class="btn btn-link">New patient? Register</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>