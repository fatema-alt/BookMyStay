<?= view('layouts/header') ?>

<section class="home-hero">
    <div class="container hero-grid">

        <div class="hero-content">
            <p class="subtitle">Luxury Hotel Booking</p>
            <h1>Experience Comfort, Elegance & Premium Stay</h1>
            <p class="hero-desc">
                Discover beautiful rooms, check availability instantly, and book your perfect stay with BookMyStay.
            </p>

            <div class="hero-actions">
                <a href="<?= site_url('room/search') ?>" class="btn-primary">Explore Rooms</a>
                <a href="#" class="btn-outline">Learn More</a>
            </div>
        </div>

        <div class="hero-card">
            <img src="<?= base_url('assets/images/logo - header.png') ?>" alt="BookMyStay">
            <h3>Premium Hotel Experience</h3>
            <p>Luxury rooms, smooth booking, and reliable stay management.</p>
        </div>

    </div>
</section>

<section class="home-search">
    <div class="container">
        <form class="home-search-box" method="get" action="<?= site_url('room/search') ?>">
            <div>
                <label>Check In</label>
                <input type="date" name="check_in" required>
            </div>

            <div>
                <label>Check Out</label>
                <input type="date" name="check_out" required>
            </div>

            <button type="submit">Search Rooms</button>
        </form>
    </div>
</section>

<section class="guest-reviews">
    <div class="container">

        <div class="section-title">
            <p class="subtitle">Guest Feedback</p>
            <h2>What Our Guests Say</h2>
        </div>

        <?php if (!empty($reviews)): ?>
            <div class="review-grid">
                <?php foreach ($reviews as $review): ?>
                    <div class="home-review-card">
                        <div class="gold-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $review['rating'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </div>

                        <p class="review-comment">
                            “<?= esc($review['comment']) ?>”
                        </p>

                        <div class="review-user">
                            <strong><?= esc($review['user_name']) ?></strong>
                            <span><?= esc($review['room_type']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                <h3>No reviews yet</h3>
                <p>Approved guest reviews will appear here.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?= view('layouts/footer') ?>