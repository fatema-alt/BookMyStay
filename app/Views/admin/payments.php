<?= view('layouts/admin_header') ?>

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <p class="subtitle">Admin Panel</p>
            <h1>Payments</h1>
        </div>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th>Transaction</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid At</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><?= esc($p['booking_code']) ?></td>

                                <td>
                                    <?= esc($p['customer_name']) ?><br>
                                    <small><?= esc($p['email']) ?></small>
                                </td>

                                <td><?= esc(ucfirst($p['payment_method'])) ?></td>

                                <td>
                                    <small><?= esc($p['transaction_id']) ?></small>
                                </td>

                                <td>৳<?= esc($p['amount']) ?></td>

                                <td>
                                    <span class="status-badge status-<?= esc($p['status']) ?>">
                                        <?= esc(ucfirst($p['status'])) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $p['paid_at'] ? date('d M Y, h:i A', strtotime($p['paid_at'])) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No payments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?= view('layouts/admin_footer') ?>