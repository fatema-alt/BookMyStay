<?= view('layouts/header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">My Account</p>
            <h1>My Bookings</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td><?= esc($b['booking_code']) ?></td>

                                <td>
                                    <?= esc($b['room_type']) ?>
                                    (<?= esc($b['room_number']) ?>)
                                </td>

                                <td>
                                    <?= esc($b['check_in']) ?> →
                                    <?= esc($b['check_out']) ?>
                                </td>

                                <td>৳<?= esc($b['total_amount']) ?></td>

                                <td>
                                    <span class="status-badge status-<?= esc($b['status']) ?>">
                                        <?= esc(ucwords(str_replace('_', ' ', $b['status']))) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (!empty($b['payment_status']) && $b['payment_status'] === 'paid'): ?>
                                        <span class="status-badge status-confirmed">Paid</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">Unpaid</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a class="action-btn approve" href="<?= site_url('my-bookings/view/' . $b['id']) ?>">
                                        View
                                    </a>

                                    <?php if (
                                        (empty($b['payment_status']) || $b['payment_status'] !== 'paid') &&
                                        !in_array($b['status'], ['cancelled', 'cancel_requested'])
                                    ): ?>
                                        <a class="action-btn approve" href="<?= site_url('payment/pay/' . $b['id']) ?>">
                                            Pay
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($b['status'] === 'confirmed'): ?>
                                        <a class="action-btn approve" href="<?= site_url('review/create/' . $b['id']) ?>">
                                            Review
                                        </a>
                                    <?php endif; ?>

                                    <?php if (in_array($b['status'], ['pending', 'confirmed'])): ?>
                                        <a class="action-btn cancel"
                                            onclick="return confirm('Send cancel request for this booking?')"
                                            href="<?= site_url('booking/cancel/' . $b['id']) ?>">
                                            Cancel Request
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($b['status'] === 'cancel_requested'): ?>
                                        <span class="status-badge status-pending">
                                            Waiting Approval
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?= view('layouts/footer') ?>