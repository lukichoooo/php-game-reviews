<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = User::register($_POST['username'], $_POST['email'], $_POST['password']);
    if ($result->success) {
        $user = User::login($_POST['email'], $_POST['password'])->data;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect('index.php');
    } else {
        $error = $result->error;
    }
}

$title = 'Register';
require __DIR__ . '/includes/header.php';
?>
<div class="form-container">
    <h2>Register</h2>
    <?php if ($error): ?><div class="error" style="display:block"><?= $error ?></div><?php endif; ?>
    <form method="post" id="register-form">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="email" required>
            <div class="error" id="email-error" style="font-size:0.85em;padding:5px"></div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <div class="error" id="pass-error" style="font-size:0.85em;padding:5px"></div>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" id="confirm" required>
            <div class="error" id="confirm-error" style="font-size:0.85em;padding:5px"></div>
        </div>
        <button type="submit" class="btn" style="width:100%">Register</button>
        <p style="margin-top:15px;text-align:center;color:#999">Already have an account? <a href="login.php" style="color:#e94560">Login</a></p>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
