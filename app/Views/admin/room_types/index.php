<?= view('layouts/admin_header') ?>

<div class="admin-main">

    <div class="admin-header">
        <p class="subtitle">Admin Panel</p>
        <h1>Manage Room Types</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="admin-actions">
        <a href="<?= site_url('admin/room-types/create') ?>">+ Add Room Type</a>
        <a href="<?= site_url('admin/dashboard') ?>">Back Dashboard</a>
    </div>


    <div class="admin-table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Base Price</th>
                    <th>Capacity</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($roomTypes)): ?>
                    <?php foreach ($roomTypes as $type): ?>
                        <tr>
                            <td>
                                <strong><?= esc($type['name']) ?></strong><br>
                                <small><?= esc($type['description']) ?></small>
                            </td>
                            <td>৳<?= esc($type['base_price']) ?></td>
                            <td>
                                Adults: <?= esc($type['max_adults']) ?><br>
                                Children: <?= esc($type['max_children']) ?>
                            </td>
                            <td>
                                <?php if (!empty($type['image'])): ?>
                                    <img class="admin-room-thumb" src="<?= base_url('assets/images/rooms/' . $type['image']) ?>"
                                        alt="Room Type">
                                <?php else: ?>
                                    <span>No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= esc($type['status']) ?>">
                                    <?= esc(ucfirst($type['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <a class="action-btn approve" href="<?= site_url('admin/room-types/edit/' . $type['id']) ?>">
                                    Edit
                                </a>

                                <a class="action-btn cancel" onclick="return confirm('Delete this room type?')"
                                    href="<?= site_url('admin/room-types/delete/' . $type['id']) ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No room types found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</section>

<?= view('layouts/admin_footer') ?>