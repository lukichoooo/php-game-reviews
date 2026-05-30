<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

$users = User::getAll()->data['users'];
$message = '';
$fileContent = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user = User::getById((int)$_POST['user_id'])->data['user'];
    $reviews = Review::getByUser($user['id'])->data;

    $text = "User Export\n";
    $text .= "========================\n";
    $text .= "ID:       {$user['id']}\n";
    $text .= "Username: {$user['username']}\n";
    $text .= "Email:    {$user['email']}\n";
    $text .= "Role:     {$user['role']}\n";
    $text .= "Registered: {$user['created_at']}\n";
    $text .= "Reviews:  " . count($reviews) . "\n\n";

    foreach ($reviews as $i => $r) {
        $text .= "Review #" . ($i + 1) . "\n";
        $text .= "  Game:    {$r['game_title']}\n";
        $text .= "  Rating:  {$r['rating']}/5\n";
        $text .= "  Content: {$r['content']}\n\n";
    }

    file_put_contents(__DIR__ . '/../users.txt', $text);
    $message = 'Exported!';
    $fileContent = file_get_contents(__DIR__ . '/../users.txt');
}

$title = 'Export User';
require __DIR__ . '/../includes/header.php';
?>
<h2 style="color:#e94560">Export User Info</h2>

<?php if ($message): ?><div class="success"><?= $message ?></div><?php endif; ?>

<form method="post" style="max-width:400px;margin:20px 0">
    <div class="form-group">
        <label>Select User</label>
        <select name="user_id" required>
            <option value="">-- Choose --</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= $u['username'] ?> (<?= $u['email'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-green">Export</button>
</form>

<a href="index.php" class="btn btn-small">Back</a>

<?php if ($fileContent): ?>
    <hr style="border-color:#333;margin:20px 0">
    <pre style="background:#16213e;padding:15px;border-radius:5px;overflow-x:auto"><?= $fileContent ?></pre>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
