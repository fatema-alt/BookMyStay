<?= view('layouts/header') ?>

<section class="profile-page">
    <div class="container">

        <div class="profile-card">
            <div class="profile-left">
                <div class="profile-avatar">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= base_url('assets/images/users/' . $user['profile_image']) ?>" alt="Profile Image">
                    <?php else: ?>
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <h2><?= esc($user['name']) ?></h2>
                <p><?= esc($user['email']) ?></p>

               


            </div>

            <div class="profile-right">
                <p class="subtitle">Account Settings</p>
                <h1>My Profile</h1>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>


                    <form method="post" action="<?= site_url('profile/update') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= esc($user['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="<?= esc($user['email']) ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= esc($user['phone']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*">
                    </div>

                    <button type="submit" class="confirm-btn">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

<?= view('layouts/footer') ?>