<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$categories = Review::getCategories()->data;
$selectedCat = isset($_GET['category']) ? (int)$_GET['category'] : null;

if ($selectedCat) {
    $reviews = Review::getByCategory($selectedCat);
} else {
    $reviews = Review::getAll();
}

$title = 'All Reviews';
require __DIR__ . '/includes/header.php';
?>
<h2 style="color:#e94560">All Reviews</h2>

<div class="cat-filters">
    <button class="btn <?= !$selectedCat ? 'active' : '' ?>" data-cat="all">All</button>
    <?php foreach ($categories as $cat): ?>
        <button class="btn <?= $selectedCat === (int)$cat['id'] ? 'active' : '' ?>" data-cat="<?= $cat['id'] ?>"><?= ($cat['name']) ?></button>
    <?php endforeach; ?>
</div>

<div class="review-grid">
    <?php foreach ($reviews as $r): ?>
        <a href="review.php?id=<?= $r['id'] ?>" class="card" data-cat="<?= $r['category_id'] ?>" style="border-bottom: 3px solid <?= ($r['accent_color']) ?>">
            <?php if ($r['image']): ?>
                <img src="uploads/<?= ($r['image']) ?>" alt="<?= ($r['game_title']) ?>">
            <?php endif; ?>
            <h3><?= ($r['game_title']) ?></h3>
            <div class="meta">by <?= ($r['username']) ?> · <?= $r['created_at'] ?></div>
            <div class="rating"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></div>
        </a>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?>
        <p style="color:#666">No reviews found.</p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
