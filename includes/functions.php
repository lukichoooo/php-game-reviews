<?php

function isLoggedIn(): bool
{
    if (isset($_SESSION['user_id'])) return true;
    if (isset($_COOKIE['remember_token'])) {
        $db = Database::getInstance();
        $user = $db->query("SELECT * FROM users WHERE id = ?", [$_COOKIE['remember_token']])->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireRole($role): void
{
    requireLogin();
    if ($_SESSION['role'] !== $role && $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

function isAdmin(): bool
{
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}
