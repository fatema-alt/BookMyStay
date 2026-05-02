<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Edit Room</h1>
        </div>

        <div class="admin-form-card">
            <form method="post" action="<?= site_url('admin/rooms/update/' . $room['id']) ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id" required>
                        <?php foreach ($roomTypes as $type): ?>
                            <option value="<?= esc($type['id']) ?>"
                                <?= $room['room_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" name="room_number" value="<?= esc($room['room_number']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Floor</label>
                        <input type="text" name="floor" value="<?= esc($room['floor']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" value="<?= esc($room['price']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="available" <?= $room['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="maintenance" <?= $room['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="inactive" <?= $room['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="confirm-btn">Update Room</button>
            </form>
        </div>

    </div>
</section>


<?= view('layouts/admin_footer') ?>