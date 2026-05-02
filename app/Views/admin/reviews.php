<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Manage Reviews</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Room Type</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $r): ?>
                            <tr>
                                <td><?= esc($r['user_name']) ?></td>
                                <td><?= esc($r['room_type']) ?></td>
                                <td>
                                    <span class="gold-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?= $i <= $r['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </span>
                                </td>
                                <td><?= esc($r['comment']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= esc($r['status']) ?>">
                                        <?= esc(ucfirst($r['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($r['status'] !== 'approved'): ?>
                                        <a class="action-btn approve"
                                           href="<?= site_url('admin/review/approve/' . $r['id']) ?>">
                                            Approve
                                        </a>
                                    <?php endif; ?>

                                    <a class="action-btn cancel"
                                       onclick="return confirm('Delete this review?')"
                                       href="<?= site_url('admin/review/delete/' . $r['id']) ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No reviews found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?= view('layouts/admin_footer') ?>