<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - BookMyStay</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/CSS/style.css') ?>">
</head>

<body>

    <div class="admin-layout">

        <aside class="admin-sidebar">
            <a href="<?= site_url('admin/dashboard') ?>" class="admin-brand">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="BookMyStay">
                <span>BookMyStay</span>
            </a>

            <nav class="admin-menu">
                <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
                <a href="<?= site_url('admin/bookings') ?>">Bookings</a>
                <a href="<?= site_url('admin/rooms') ?>">Rooms</a>
                <a href="<?= site_url('admin/room-types') ?>">Room Types</a>
                <a href="<?= site_url('admin/reviews') ?>">Reviews</a>
                 <a href="<?= site_url('admin/payments') ?>">Payments</a>
                <a href="<?= site_url('/') ?>">View Website</a>
                
            </nav>
        </aside>

        <main class="admin-main">

            <div class="admin-topbar">
                <div>
                    <h3>Admin Panel</h3>
                    <p>Manage hotel bookings, rooms, reviews and payments</p>
                </div>
               

                <div class="profile-dropdown">
                    <button class="profile-btn">
                        <div class="user-avatar">
                            <?php if (!empty(session('profile_image'))): ?>
                                <img src="<?= base_url('assets/images/users/' . session('profile_image')) ?>" alt="Profile">
                            <?php else: ?>
                                <?= strtoupper(substr(session('user_name'), 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        <span><?= esc(session()->get('user_name')) ?></span>
                        <small>▼</small>
                    </button>

                    <div class="dropdown-menu">
                        <a href="<?= site_url('profile') ?>">My Profile</a>

                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a>
                        <?php else: ?>
                            <a href="<?= site_url('my-bookings') ?>">My Bookings</a>
                        <?php endif; ?>

                        <a href="<?= site_url('logout') ?>">Logout</a>
                    </div>
                </div>


            </div>