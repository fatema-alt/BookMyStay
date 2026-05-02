<?= view('layouts/header') ?>
<?php if (session()->getFlashdata('success')): ?>
    <div style="background: #d1fae5; padding: 15px; margin-bottom:20px; border-radius:10px;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div style="background: #fee2e2; padding: 15px; margin-bottom:20px; border-radius:10px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<section class="room-search-page">
    <div class="container">

        <div class="search-page-header">
            <p class="subtitle">Available Rooms</p>
            <h1>Choose Your Perfect Room</h1>

            <?php if (!empty($check_in) && !empty($check_out)): ?>
                <p class="search-date">
                    <?= esc($check_in) ?> to <?= esc($check_out) ?>
                </p>
            <?php endif; ?>

            <form class="result-search-box" method="get" action="<?= site_url('room/search') ?>">
                <div>
                    <label>Check In</label>
                    <input type="date" name="check_in" value="<?= esc($check_in) ?>" required>
                </div>

                <div>
                    <label>Check Out</label>
                    <input type="date" name="check_out" value="<?= esc($check_out) ?>" required>
                </div>

                <button type="submit">Search Again</button>
            </form>
        </div>

        <?php if (!empty($check_in) && !empty($check_out)): ?>

            <?php if (!empty($rooms)): ?>
                <div class="room-grid">
                    <?php foreach ($rooms as $room): ?>
                        <div class="room-card">
                            <div class="room-image">
                                <img src="<?= base_url('assets/images/rooms/' . $room['image']) ?>"
                                    alt="<?= esc($room['room_type']) ?>">
                            </div>

                            <div class="room-content">
                                <span class="room-type"><?= esc($room['room_type']) ?></span>
                                <h3>Room <?= esc($room['room_number']) ?></h3>
                                <div class="room-rating">
                                    <span class="gold-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?= $i <= round($room['avg_rating']) ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </span>

                                    <small>
                                        <?= number_format($room['avg_rating'], 1) ?>
                                        (<?= esc($room['review_count']) ?> reviews)
                                    </small>
                                </div>

                                <p><?= esc($room['description']) ?></p>

                                <div class="room-meta">
                                    <span>Floor: <?= esc($room['floor']) ?></span>
                                    <span>Adults: <?= esc($room['max_adults']) ?></span>
                                    <span>Children: <?= esc($room['max_children']) ?></span>
                                </div>

                                <div class="room-footer">
                                    <strong>৳<?= esc($room['price']) ?> / night</strong>

                                    <a href="<?= site_url('booking/create/' . $room['id']) ?>" class="book-btn">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-box">
                    <h3>No rooms available</h3>
                    <p>Please try another date range.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-box">
                <h3>Search rooms by date</h3>
                <p>Select check-in and check-out dates to see available rooms.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?= view('layouts/footer') ?>