
<div class="admin-main">

    <div class="admin-header">
        <p class="subtitle">Admin Panel</p>
        <h1>Add Room Type</h1>
    </div>

    <div class="admin-form-card">
        <form method="post" action="<?= site_url('admin/room-types/store') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Room Type Name</label>
                <input type="text" name="name" value="<?= esc($roomType['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= esc($roomType['description']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Base Price</label>
                    <input type="number" name="base_price" step="0.01" value="<?= esc($roomType['base_price']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Room Type Image</label>

                    <?php if (!empty($roomType['image'])): ?>
                        <div class="room-preview">
                            <img src="<?= base_url('assets/images/rooms/' . $roomType['image']) ?>" alt="Room Type Image">
                        </div>
                    <?php endif; ?>

                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Max Adults</label>
                    <input type="number" name="max_adults" value="<?= esc($roomType['max_adults']) ?>" min="1">
                </div>

                <div class="form-group">
                    <label>Max Children</label>
                    <input type="number" name="max_children" value="<?= esc($roomType['max_children']) ?>" min="0">
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $roomType['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $roomType['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="confirm-btn">Update Room Type</button>
        </form>
    </div>

</div>

<?= view('layouts/admin_footer') ?>