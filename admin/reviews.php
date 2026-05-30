<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    Review::delete((int)$_GET['delete']);
    redirect('reviews.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    Review::update((int)$_POST['review_id'], $_POST['game_title'], $_POST['content'], (int)$_POST['rating'], (int)$_POST['category_id']);
    redirect('reviews.php');
}

$reviews = Review::getAll()->data;
$categories = Review::getCategories()->data;

$editReview = null;
if (isset($_GET['edit'])) {
    $editReview = Review::getById((int)$_GET['edit'])->data;
}

$title = 'Manage Reviews';
require __DIR__ . '/../includes/header.php';
?>
<h2 style="color:#e94560">Reviews</h2>

<?php if ($editReview): ?>
    <form method="post" style="max-width:500px;margin-bottom:20px;background:#16213e;padding:20px;border-radius:10px">
        <input type="hidden" name="review_id" value="<?= $editReview['id'] ?>">
        <div class="form-group">
            <label>Game Title</label>
            <input type="text" name="game_title" value="<?= $editReview['game_title'] ?>" required>
        </div>
        <div class="form-group">
            <label>Rating</label>
            <select name="rating">
                <?php for ($i=1;$i<=5;$i++): ?>
                    <option value="<?= $i ?>" <?= (int)$editReview['rating'] === $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id">
                <option value="0">None</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (int)$editReview['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Content</label>
            <textarea name="content" required><?= $editReview['content'] ?></textarea>
        </div>
        <input type="hidden" name="update" value="1">
        <button type="submit" class="btn btn-small">Update</button>
        <a href="reviews.php" class="btn btn-small">Cancel</a>
    </form>
<?php endif; ?>

<table>
    <tr><th>Game</th><th>Author</th><th>Rating</th><th>Actions</th></tr>
    <?php foreach ($reviews as $r): ?>
        <tr>
            <td><a href="../review.php?id=<?= $r['id'] ?>" style="color:#e94560"><?= $r['game_title'] ?></a></td>
            <td><?= $r['username'] ?></td>
            <td><?= $r['rating'] ?>/5</td>
            <td>
                <a href="reviews.php?edit=<?= $r['id'] ?>" class="btn btn-small">Edit</a>
                <a href="reviews.php?delete=<?= $r['id'] ?>" class="btn btn-small btn-red" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="index.php" class="btn btn-small">Back</a>
<?php require __DIR__ . '/../includes/footer.php'; ?>
