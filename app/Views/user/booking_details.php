<?= view('layouts/header') ?>

<section class="booking-details-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Booking Details</p>
            <h1><?= esc($booking['booking_code']) ?></h1>
        </div>

        <div class="booking-detail-grid">

            <div class="booking-detail-card">
                <img src="<?= base_url('assets/images/rooms/' . $booking['image']) ?>"
                    alt="<?= esc($booking['room_type']) ?>">

                <div class="booking-detail-content">
                    <span class="room-type"><?= esc($booking['room_type']) ?></span>
                    <h2>Room <?= esc($booking['room_number']) ?></h2>
                    <p><?= esc($booking['description']) ?></p>

                    <div class="room-meta">
                        <span>Floor: <?= esc($booking['floor']) ?></span>
                        <span>Nights: <?= esc($booking['total_nights']) ?></span>
                    </div>
                </div>
            </div>

            <div class="booking-summary-card">
                <h2>Booking Summary</h2>

                <div class="summary-row">
                    <span>Check In</span>
                    <strong><?= esc($booking['check_in']) ?></strong>
                </div>

                <div class="summary-row">
                    <span>Check Out</span>
                    <strong><?= esc($booking['check_out']) ?></strong>
                </div>

                <div class="summary-row">
                    <span>Total Amount</span>
                    <strong>৳<?= esc($booking['total_amount']) ?></strong>
                </div>

                <div class="summary-row">
                    <span>Booking Status</span>
                    <strong>
                        <span class="status-badge status-<?= esc($booking['status']) ?>">
                            <?= esc(ucfirst($booking['status'])) ?>
                        </span>
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Payment</span>
                    <strong>
                        <?php if ($booking['payment_status'] === 'paid'): ?>
                            <span class="status-badge status-confirmed">Paid</span>
                        <?php else: ?>
                            <span class="status-badge status-pending">Unpaid</span>
                        <?php endif; ?>
                    </strong>
                </div>

                <?php if (!empty($booking['special_request'])): ?>
                    <div class="special-box">
                        <strong>Special Request</strong>
                        <p><?= esc($booking['special_request']) ?></p>
                    </div>
                <?php endif; ?>

                <div class="detail-actions">
                    <?php if ($booking['payment_status'] !== 'paid' && $booking['status'] !== 'cancelled'): ?>
                        <a class="action-btn approve" href="<?= site_url('payment/pay/' . $booking['id']) ?>">Pay Now</a>
                    <?php endif; ?>

                    <?php if ($booking['status'] === 'confirmed'): ?>
                        <a class="action-btn approve" href="<?= site_url('review/create/' . $booking['id']) ?>">Review</a>
                    <?php endif; ?>

                    <?php if ($booking['status'] !== 'cancelled'): ?>
                        <a class="action-btn cancel" onclick="return confirm('Cancel this booking?')"
                            href="<?= site_url('booking/cancel/' . $booking['id']) ?>">
                            Cancel
                        </a>
                    <?php endif; ?>
                    <?php if ($booking['payment_status'] === 'paid'): ?>
                        <a class="action-btn approve" href="<?= site_url('invoice/download/' . $booking['id']) ?>">
                            Download Invoice
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</section>

<?= view('layouts/footer') ?>