<?= view('layouts/header') ?>

<section class="review-page">
    <div class="review-card">

        <div class="review-room-info">
            <p class="subtitle">Share Experience</p>
            <h1>Rate Your Stay</h1>

            <div class="review-room-box">
                <span><?= esc($booking['room_type']) ?></span>
                <strong>Room <?= esc($booking['room_number']) ?></strong>
            </div>
        </div>

        <form method="post" action="<?= site_url('review/store') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="booking_id" value="<?= esc($booking['id']) ?>">
            <input type="hidden" name="room_type_id" value="<?= esc($booking['room_type_id']) ?>">

            <div class="form-group">
    <label>Your Rating</label>

    <div class="star-rating">
        <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
        <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
        <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
        <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
        <input type="radio" name="rating" id="star1" value="1" required><label for="star1">★</label>
    </div>
</div>

            <div class="form-group">
                <label>Your Review</label>
                <textarea name="comment" rows="5" placeholder="Write your experience..." required></textarea>
            </div>

            <button type="submit" class="confirm-btn">Submit Review</button>
        </form>

    </div>
</section>

<?= view('layouts/footer') ?>