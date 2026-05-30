<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$categories = Review::getCategories()->data;
$myReviews = Review::getByUser($_SESSION['user_id'])->data;
$message = '';
$editReview = null;

// Handle edit request
if (isset($_GET['edit'])) {
    $editReview = Review::getById((int)$_GET['edit'])->data;
    if (!$editReview || ($editReview['user_id'] != $_SESSION['user_id'] && !isAdmin())) {
        redirect('dashboard.php');
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $r = Review::getById((int)$_GET['delete'])->data;
    if ($r && ($r['user_id'] == $_SESSION['user_id'] || isAdmin())) {
        Review::delete((int)$_GET['delete']);
        $message = 'Review deleted.';
    }
    redirect('dashboard.php');
}

// Handle create / update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gameTitle = $_POST['game_title'];
    $content = $_POST['content'];
    $rating = (int)$_POST['rating'];
    $accentColor = $_POST['accent_color'] ?? '#ff6600';
    $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $editId = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $imageName);
    }

    if ($editId) {
        if ($imageName) {
            $old = Review::getById($editId)->data;
            if ($old && $old['image']) {
                $oldPath = UPLOAD_DIR . $old['image'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $db = Database::getInstance();
            $db->query(
                "UPDATE reviews SET game_title=?, content=?, rating=?, image=?, accent_color=?, category_id=? WHERE id=?",
                [$gameTitle, $content, $rating, $imageName, $accentColor, $categoryId, $editId]
            );
        } else {
            Review::update($editId, $gameTitle, $content, $rating, $categoryId);
        }
        $message = 'Review updated!';
    } else {
        Review::create($_SESSION['user_id'], $gameTitle, $content, $rating, $imageName, $accentColor, $categoryId);
        $message = 'Review created!';
    }

    redirect('dashboard.php');
}

$title = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<h2 style="color:#e94560">Dashboard</h2>
<p>Welcome, <?= ($_SESSION['username']) ?>!</p>

<?php if ($message): ?><div class="success"><?= ($message) ?></div><?php endif; ?>

<hr style="border-color:#333;margin:20px 0">

<h3><?= $editReview ? 'Edit Review' : 'Write a New Review' ?></h3>
<form method="post" enctype="multipart/form-data" style="max-width:600px;margin:15px 0 30px">
    <?php if ($editReview): ?>
        <input type="hidden" name="edit_id" value="<?= $editReview['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
        <label>Game Title</label>
        <input type="text" name="game_title" value="<?= ($editReview['game_title'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Category</label>
        <select name="category_id">
            <option value="">None</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($editReview && (int)$editReview['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Rating (1-5)</label>
        <select name="rating" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= ($editReview && (int)$editReview['rating'] === $i) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Review</label>
        <textarea name="content" required><?= ($editReview['content'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Screenshot (optional)</label>
        <input type="file" name="image" accept="image/*">
        <?php if ($editReview && $editReview['image']): ?>
            <p style="color:#999;font-size:0.85em">Current: <?= ($editReview['image']) ?> (upload a new one to replace)</p>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label>Accent Color</label>
        <div class="color-section">
            <input type="color" id="accent-color" name="accent_color" value="<?= ($editReview['accent_color'] ?? '#ff6600') ?>">
            <div class="color-values">
                HEX: <span id="hex-val"><?= ($editReview['accent_color'] ?? '#ff6600') ?></span> |
                RGBA: <span id="rgba-val">rgba(255,102,0,1)</span> |
                HSL: <span id="hsl-val">hsl(24,100%,50%)</span>
            </div>
        </div>
    </div>
    <button type="submit" class="btn"><?= $editReview ? 'Update Review' : 'Submit Review' ?></button>
</form>

<h3>My Reviews</h3>
<?php if (empty($myReviews)): ?>
    <p style="color:#666">You haven't written any reviews yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Game</th>
            <th>Rating</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($myReviews as $r): ?>
            <tr>
                <td><a href="review.php?id=<?= $r['id'] ?>" style="color:#e94560"><?= ($r['game_title']) ?></a></td>
                <td><?= str_repeat('★', $r['rating']) ?></td>
                <td><?= $r['created_at'] ?></td>
                <td>
                    <a href="dashboard.php?edit=<?= $r['id'] ?>" class="btn btn-small">Edit</a>
                    <a href="dashboard.php?delete=<?= $r['id'] ?>" class="btn btn-small btn-red" onclick="return confirm('Delete this review?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
