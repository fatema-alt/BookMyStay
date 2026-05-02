<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Manage Rooms</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="admin-actions">
            <a href="<?= site_url('admin/rooms/create') ?>">+ Add Room</a>
            <a href="<?= site_url('admin/dashboard') ?>">Back Dashboard</a>
        </div>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Room No</th>
                        <th>Room Type</th>
                        <th>Floor</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($rooms)): ?>
                        <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($room['image'])): ?>
                                        <img class="admin-room-thumb" src="<?= base_url('assets/images/rooms/' . $room['image']) ?>"
                                            alt="Room">
                                    <?php else: ?>
                                        <span>No image</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= esc($room['room_number']) ?></strong></td>
                                <td><?= esc($room['room_type']) ?></td>
                                <td><?= esc($room['floor']) ?></td>
                                <td>৳<?= esc($room['price']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= esc($room['status']) ?>">
                                        <?= esc(ucfirst($room['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="action-btn approve" href="<?= site_url('admin/rooms/edit/' . $room['id']) ?>">
                                        Edit
                                    </a>

                                    <a class="action-btn cancel" onclick="return confirm('Delete this room?')"
                                        href="<?= site_url('admin/rooms/delete/' . $room['id']) ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No rooms found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>


<?= view('layouts/admin_footer') ?>