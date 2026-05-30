<?php
$db = new PDO('sqlite:' . __DIR__ . '/../gamereviews.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    );

    CREATE TABLE IF NOT EXISTS reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        game_title TEXT NOT NULL,
        content TEXT NOT NULL,
        rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
        image TEXT,
        accent_color TEXT DEFAULT '#ff6600',
        category_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS review_likes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        review_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
        UNIQUE(user_id, review_id)
    );
");

// Seed categories if empty
$count = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
if ($count == 0) {
    $db->exec("
        INSERT INTO categories (name) VALUES ('Action'), ('RPG'), ('Strategy'), ('Shooter'), ('Adventure'), ('Sports'), ('Puzzle'), ('Horror');
    ");
}

// Seed admin user if not exists
$check = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$check->execute(['admin']);
if ($check->fetchColumn() == 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute(['admin', 'admin@example.com', $hash]);
}

echo "Database initialized!";
