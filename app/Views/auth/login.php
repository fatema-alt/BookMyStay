<?= view('layouts/header') ?>

<section class="auth-page">
    <div class="auth-card">
        <p class="subtitle">Welcome Back</p>
        <h1>Login to BookMyStay</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('login/check') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="confirm-btn">Login</button>
        </form>

        <p class="auth-link">
            Don’t have an account?
            <a href="<?= site_url('register') ?>">Create Account</a>
        </p>
    </div>
</section>

<?= view('layouts/footer') ?>