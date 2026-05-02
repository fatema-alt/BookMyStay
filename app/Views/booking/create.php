<?= view('layouts/header') ?>

<section class="booking-page">
    <div class="container booking-grid">

        <div class="booking-room-card">
            <img src="<?= base_url('assets/images/rooms/' . $room['image']) ?>" alt="<?= esc($room['room_type']) ?>">

            <div class="booking-room-content">
                <span class="room-type"><?= esc($room['room_type']) ?></span>
                <h1>Room <?= esc($room['room_number']) ?></h1>
                <p><?= esc($room['description']) ?></p>

                <div class="room-meta">
                    <span>Floor: <?= esc($room['floor']) ?></span>
                    <span>Adults: <?= esc($room['max_adults']) ?></span>
                    <span>Children: <?= esc($room['max_children']) ?></span>
                </div>

                <h3>৳<?= esc($room['price']) ?> / night</h3>
            </div>
        </div>

        <div class="booking-form-card">
            <p class="subtitle">Confirm Booking</p>
            <h2>Book Your Stay</h2>

            <form method="post" action="<?= site_url('booking/store') ?>">
                <?= csrf_field() ?>

                <input type="hidden" name="room_id" value="<?= esc($room['id']) ?>">

                <div class="form-group">
                    <label>Check In</label>
                    <input type="date" name="check_in" required>
                </div>

                <div class="form-group">
                    <label>Check Out</label>
                    <input type="date" name="check_out" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Adults</label>
                        <input type="number" name="adults" min="1" value="1" required>
                    </div>

                    <div class="form-group">
                        <label>Children</label>
                        <input type="number" name="children" min="0" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Special Request</label>
                    <textarea name="special_request" rows="4" placeholder="Any special request?"></textarea>
                </div>

                <button type="submit" class="confirm-btn">Confirm Booking</button>
            </form>
        </div>

    </div>
</section>

<?= view('layouts/footer') ?>