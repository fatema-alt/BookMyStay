<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Add Room</h1>
        </div>

        <div class="admin-form-card">
            <form method="post" action="<?= site_url('admin/rooms/store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id" required>
                        <option value="">Select Room Type</option>
                        <?php foreach ($roomTypes as $type): ?>
                            <option value="<?= esc($type['id']) ?>">
                                <?= esc($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" name="room_number" placeholder="101" required>
                    </div>

                    <div class="form-group">
                        <label>Floor</label>
                        <input type="text" name="floor" placeholder="1st Floor">
                    </div>
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" placeholder="4500" required>
                </div>
                <div class="form-group">
                    <label>Room Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="available">Available</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="confirm-btn">Save Room</button>
            </form>
        </div>

    </div>
</section>


<?= view('layouts/admin_footer') ?>