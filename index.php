<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$reviews = Review::getAll(6)->data;

$title = 'Home';
require __DIR__ . '/includes/header.php';
?>
<div class="hero">
    <h1>Welcome to GameReviews</h1>
    <p>Read reviews, share your thoughts, and discover your next favorite game.</p>
    <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn" style="margin-top:20px">Get Started</a>
    <?php endif; ?>
</div>

<h2 style="color:#e94560">Latest Reviews</h2>
<div class="review-grid">
    <?php foreach ($reviews as $r): ?>
        <a href="review.php?id=<?= $r['id'] ?>" class="card" style="border-bottom: 3px solid <?= ($r['accent_color']) ?>">
            <?php if ($r['image']): ?>
                <img src="uploads/<?= ($r['image']) ?>" alt="<?= ($r['game_title']) ?>">
            <?php endif; ?>
            <h3><?= ($r['game_title']) ?></h3>
            <div class="meta">by <?= ($r['username']) ?> · <?= $r['created_at'] ?></div>
            <div class="rating"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></div>
        </a>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?>
        <p style="color:#666">No reviews yet. Be the first!</p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
