<?= view('layouts/admin_header') ?>
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
                <input type="text" name="name" placeholder="Deluxe Room" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Room description"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Base Price</label>
                    <input type="number" name="base_price" step="0.01" placeholder="4500" required>
                </div>

                <div class="form-group">
                    <label>Room Type Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Max Adults</label>
                    <input type="number" name="max_adults" value="2" min="1">
                </div>

                <div class="form-group">
                    <label>Max Children</label>
                    <input type="number" name="max_children" value="0" min="0">
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="confirm-btn">Save Room Type</button>
        </form>
    </div>

</div>

<?= view('layouts/admin_footer') ?>