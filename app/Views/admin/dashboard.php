<?= view('layouts/admin_header') ?>

<div class="admin-content">

    <h2 class="admin-page-title">Admin Dashboard</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <p>Total Bookings</p>
            <h3><?= $totalBookings ?></h3>
        </div>

        <div class="dashboard-card">
            <p>Confirmed</p>
            <h3><?= $confirmedBookings ?></h3>
        </div>

        <div class="dashboard-card">
            <p>Pending</p>
            <h3><?= $pendingBookings ?></h3>
        </div>

        <div class="dashboard-card">
            <p>Cancelled</p>
            <h3><?= $cancelledBookings ?></h3>
        </div>

        <div class="dashboard-card revenue">
            <p>Total Revenue</p>
            <h3>৳ <?= number_format($totalRevenue, 2) ?></h3>
        </div>

        <div class="dashboard-card">
            <p>Total Users</p>
            <h3><?= $totalCustomers ?></h3>
        </div>

    </div>

    <div class="chart-card">
        <h3>Monthly Revenue</h3>
        <canvas id="revenueChart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueCanvas = document.getElementById('revenueChart');

if (revenueCanvas) {
    new Chart(revenueCanvas, {
        type: 'line',
        data: {
            labels: <?= $months ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= $revenues ?>,
                borderColor: '#D4AF37',
                backgroundColor: 'rgba(212,175,55,0.18)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#D4AF37',
                pointBorderColor: '#050505',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}
</script>

<?= view('layouts/admin_footer') ?>

