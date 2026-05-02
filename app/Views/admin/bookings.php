<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Manage Bookings</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <div class="booking-filter-card">

            <form method="get" action="<?= site_url('admin/bookings') ?>" class="booking-search-form">
                <input type="text" name="search" placeholder="Search booking code, customer, room..."
                    value="<?= esc($search ?? '') ?>">

                <button type="submit">Search</button>

                <?php if (!empty($search)): ?>
                    <a href="<?= site_url('admin/bookings') ?>" class="reset-btn">Reset</a>
                <?php endif; ?>
            </form>

            <div class="booking-filter-tabs">
                <?php
                $filters = [
                    'all' => 'All',
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'cancel_requested' => 'Cancel Requested',
                    'cancelled' => 'Cancelled',
                    'completed' => 'Completed',
                ];
                ?>

                <?php foreach ($filters as $key => $label): ?>
                    <a href="<?= site_url('admin/bookings?status=' . $key) ?>"
                        class="<?= ($activeStatus ?? 'all') === $key ? 'active' : '' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Customer</th>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= esc($booking['booking_code']) ?></td>

                                <td>
                                    <?= esc($booking['customer_name']) ?><br>
                                    <small><?= esc($booking['email']) ?></small>
                                </td>

                                <td>
                                    <?= esc($booking['room_type']) ?><br>
                                    <small>Room <?= esc($booking['room_number']) ?></small>
                                </td>

                                <td><?= esc($booking['check_in']) ?></td>
                                <td><?= esc($booking['check_out']) ?></td>
                                <td>৳<?= esc($booking['total_amount']) ?></td>

                                <td>
                                    <span class="status-badge status-<?= esc($booking['status']) ?>">
                                        <?= esc(ucfirst($booking['status'])) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (!empty($booking['payment_status']) && $booking['payment_status'] === 'paid'): ?>
                                        <span class="status-badge status-confirmed">Paid</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">Unpaid</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($booking['status'] === 'pending'): ?>
                                        <a class="action-btn approve"
                                            href="<?= site_url('admin/booking/approve/' . $booking['id']) ?>">
                                            Approve
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($booking['status'] === 'cancel_requested'): ?>
                                        <a class="action-btn cancel" onclick="return confirm('Approve this cancel request?')"
                                            href="<?= site_url('admin/booking/cancel/' . $booking['id']) ?>">
                                            Approve Cancel
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($booking['status'] !== 'cancelled' && $booking['status'] !== 'completed'): ?>
                                        <a class="action-btn cancel" onclick="return confirm('Cancel this booking?')"
                                            href="<?= site_url('admin/booking/cancel/' . $booking['id']) ?>">
                                            Cancel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align:center;">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?= view('layouts/admin_footer') ?>