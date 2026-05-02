<?= view('layouts/header') ?>

<section class="success-page">
    <div class="container">

        <div class="success-box">
            <h1>🎉 Booking Confirmed!</h1>

            <p>Your booking has been successfully placed.</p>

            <div class="booking-details">
                <p><strong>Booking Code:</strong> <?= esc($booking->booking_code) ?></p>
                <p><strong>Check In:</strong> <?= esc($booking->check_in) ?></p>
                <p><strong>Check Out:</strong> <?= esc($booking->check_out) ?></p>
                <p><strong>Total Amount:</strong> ৳<?= esc($booking->total_amount) ?></p>
                <p><strong>Status:</strong> <?= esc($booking->status) ?></p>
            </div>
            <a href="<?= site_url('payment/pay/' . $booking->id) ?>" class="btn-primary">
            Pay Now
             </a> 
            <a href="<?= site_url('room/search') ?>" class="btn-primary">
                Book Another Room
            </a>
            

        </div>

    </div>
</section>

<?= view('layouts/footer') ?>