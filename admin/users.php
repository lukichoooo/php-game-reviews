<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    User::delete((int)$_GET['delete']);
    redirect('users.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    User::updateRole((int)$_POST['user_id'], $_POST['role']);
    redirect('users.php');
}

$users = User::getAll()->data['users'];

$title = 'Manage Users';
require __DIR__ . '/../includes/header.php';
?>
<h2 style="color:#e94560">Users</h2>
<table>
    <tr><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['username'] ?></td>
            <td><?= $u['email'] ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <select name="role" onchange="this.form.submit()">
                        <?php foreach (['user','moderator','admin'] as $r): ?>
                            <option value="<?= $r ?>" <?= $u['role'] === $r ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
            <td>
                <a href="users.php?delete=<?= $u['id'] ?>" class="btn btn-small btn-red" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="index.php" class="btn btn-small">Back</a>
<?php require __DIR__ . '/../includes/footer.php'; ?>
