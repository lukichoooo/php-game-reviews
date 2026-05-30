<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$review = Review::getById($id)->data;
if (!$review) {
    http_response_code(404);
    $title = 'Not Found';
    require __DIR__ . '/includes/header.php';
    echo '<p style="text-align:center;margin:50px 0;font-size:1.2em">Review not found.</p>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$likes = Review::getLikesCount($id)->data;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like']) && isLoggedIn()) {
    Review::toggleLike($_SESSION['user_id'], $id);
    redirect('review.php?id=' . $id);
}

$cats = Review::getCategories()->data;
$catName = '';
foreach ($cats as $c) {
    if ((int)$c['id'] === (int)$review['category_id']) {
        $catName = $c['name'];
        break;
    }
}

$title = $review['game_title'];
require __DIR__ . '/includes/header.php';
?>
<div class="review-detail" style="border-left: 4px solid <?= ($review['accent_color']) ?>">
    <h1><?= ($review['game_title']) ?></h1>
    <div class="meta">
        by <?= ($review['username']) ?> · <?= $review['created_at'] ?>
        <?php if ($catName): ?> · Category: <?= $catName ?><?php endif; ?>
    </div>
    <div class="rating"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></div>

    <?php if ($review['image']): ?>
        <img src="uploads/<?= ($review['image']) ?>" alt="<?= ($review['game_title']) ?>">
    <?php endif; ?>

    <div class="content"><?= nl2br($review['content']) ?></div>

    <form method="post" style="margin-top:20px">
        <button type="submit" name="like" class="btn btn-small btn-green">
            ♥ Like (<?= $likes ?>)
        </button>
    </form>

    <?php if (isLoggedIn() && ($_SESSION['user_id'] == $review['user_id'] || isAdmin())): ?>
        <div style="margin-top:15px">
            <a href="dashboard.php?edit=<?= $review['id'] ?>" class="btn btn-small">Edit</a>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
