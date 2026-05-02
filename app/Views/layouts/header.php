<?php
$notifications = [];
$unreadCount = 0;

if (session()->get('is_logged_in')) {
    $db = \Config\Database::connect();

    $notifications = $db->table('notifications')
        ->where('user_id', session()->get('user_id'))
        ->orderBy('id', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    $unreadCount = $db->table('notifications')
        ->where('user_id', session()->get('user_id'))
        ->where('is_read', 0)
        ->countAllResults();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>BookMyStay</title>
    <link rel="fevicon" href="<?= base_url('assets/images/logo - header.css') ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/CSS/style.css') ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-flex">

            <a href="<?= site_url('/') ?>" class="brand">
                <img src="<?= base_url('assets/images/logo - header.png') ?>" alt="BookMyStay Logo">
                <span>BookMyStay</span>
            </a>
            <nav class="main-nav">

                <a href="<?= site_url('/') ?>">Home</a>
                <a href="<?= site_url('room/search') ?>">Rooms</a>

                <?php if (session()->get('is_logged_in')): ?>

                    <?php if (session()->get('user_role') === 'admin'): ?>
                        <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
                    <?php else: ?>
                        <a href="<?= site_url('my-bookings') ?>">My Bookings</a>
                    <?php endif; ?>

                    <div class="notification-dropdown">
                        <button class="notification-btn" onclick="toggleNotification()">
                            🔔
                            <?php if ($unreadCount > 0): ?>
                                <span><?= esc($unreadCount) ?></span>
                            <?php endif; ?>
                        </button>

                        <div id="notificationMenu" class="notification-menu">
                            <h4>Notifications</h4>

                            <?php if (!empty($notifications)): ?>
                                <?php foreach ($notifications as $n): ?>
                                    <a href="<?= site_url('notifications/read/' . $n['id']) ?>"
                                        class="notification-item <?= $n['is_read'] == 0 ? 'unread' : '' ?>">
                                        <strong><?= esc($n['title']) ?></strong>
                                        <p><?= esc($n['message']) ?></p>
                                        <small><?= esc($n['created_at']) ?></small>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-notification">No notifications yet.</p>
                            <?php endif; ?>
                        </div>
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

                <?php else: ?>

                    <a href="<?= site_url('login') ?>" class="nav-btn">Login</a>
                    <a href="<?= site_url('register') ?>" class="nav-outline">Register</a>

                <?php endif; ?>

            </nav>
        </div>
    </header>
    <script>
function toggleNotification() {
    const menu = document.getElementById('notificationMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// click outside = close
window.addEventListener('click', function(e) {
    const btn = document.querySelector('.notification-btn');
    const menu = document.getElementById('notificationMenu');

    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
    }
});
</script>