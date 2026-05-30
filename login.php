<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::login($_POST['email'], $_POST['password'])->data;
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if (isset($_POST['remember'])) {
            setcookie('remember_token', $user['id'], time() + 86400 * 30, '/');
        }

        redirect('index.php');
    } else {
        $error = 'Invalid email or password';
    }
}

$title = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="form-container">
    <h2>Login</h2>
    <?php if ($error): ?><div class="error" style="display:block"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> Remember me</label>
        </div>
        <button type="submit" class="btn" style="width:100%">Login</button>
        <p style="margin-top:15px;text-align:center;color:#999">No account? <a href="register.php" style="color:#e94560">Register</a></p>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
