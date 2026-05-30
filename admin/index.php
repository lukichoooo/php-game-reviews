<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

$userCount = Database::getInstance()->query("SELECT COUNT(*) FROM users")->fetchColumn();
$reviewCount = Database::getInstance()->query("SELECT COUNT(*) FROM reviews")->fetchColumn();

$title = 'Admin';
require __DIR__ . '/../includes/header.php';
?>
<h2 style="color:#e94560">Admin Panel</h2>
<div style="display:flex;gap:20px;margin:20px 0;flex-wrap:wrap">
    <div style="background:#16213e;padding:20px;border-radius:10px;flex:1;min-width:120px;box-shadow:0 4px 15px rgba(0,0,0,0.3)">
        <strong style="font-size:2em"><?= $userCount ?></strong><br>Users
    </div>
    <div style="background:#16213e;padding:20px;border-radius:10px;flex:1;min-width:120px;box-shadow:0 4px 15px rgba(0,0,0,0.3)">
        <strong style="font-size:2em"><?= $reviewCount ?></strong><br>Reviews
    </div>
</div>
<div style="display:flex;gap:10px;flex-wrap:wrap">
    <a href="users.php" class="btn">Users</a>
    <a href="reviews.php" class="btn">Reviews</a>
    <a href="export_user.php" class="btn btn-green">Export</a>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
