<?= view('layouts/header') ?>

<section class="auth-page">
    <div class="auth-card">
        <p class="subtitle">Create Account</p>
        <h1>Register Now</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('register/store') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter phone number">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create password" required>
            </div>

            <button type="submit" class="confirm-btn">Create Account</button>
        </form>

        <p class="auth-link">
            Already have an account?
            <a href="<?= site_url('login') ?>">Login</a>
        </p>
    </div>
</section>

<?= view('layouts/footer') ?>