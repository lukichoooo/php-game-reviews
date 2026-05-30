<?php

class Review
{
    public static function create(
        int $userId,
        string $gameTitle,
        string $content,
        string $rating,
        mixed $image,
        string $accentColor,
        int $categoryId
    ): ResponseDto {
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO reviews (user_id, game_title, content, rating, image, accent_color, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$userId, $gameTitle, $content, $rating, $image, $accentColor, $categoryId]
        );
        return new ResponseDto(
            success: true,
            data: ['id' => $db->lastInsertId()]
        );
    }

    public static function getById(int $id): ResponseDto
    {
        $db = Database::getInstance();
        return new ResponseDto(
            success: true,
            data: $db->query(
                "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.id = ?",
                [$id]
            )->fetch()
        );
    }

    public static function getAll(?int $limit = null, int $offset = 0): ResponseDto
    {
        $db = Database::getInstance();
        $sql = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
        if ($limit) $sql .= " LIMIT $limit OFFSET $offset";
        return new ResponseDto(
            success: true,
            data: $db->query($sql)->fetchAll()
        );
    }

    public static function getByUser(int $userId): ResponseDto
    {
        $db = Database::getInstance();
        return new ResponseDto(
            success: true,
            data: $db->query(
                "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.user_id = ? ORDER BY r.created_at DESC",
                [$userId]
            )->fetchAll()
        );
    }

    public static function getByCategory(int $categoryId): ResponseDto
    {
        $db = Database::getInstance();
        return new ResponseDto(
            success: true,
            data: $db->query(
                "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.category_id = ? ORDER BY r.created_at DESC",
                [$categoryId]
            )->fetchAll()
        );
    }

    public static function update(
        int $id,
        string $gameTitle,
        string $content,
        int  $rating,
        int $categoryId
    ): ResponseDto {
        $db = Database::getInstance();
        return new ResponseDto(
            success: true,
            data: $db->query(
                "UPDATE reviews SET game_title = ?, content = ?, rating = ?, category_id = ? WHERE id = ?",
                [$gameTitle, $content, $rating, $categoryId, $id]
            )
        );
    }

    public static function delete(int $id): ResponseDto
    {
        $db = Database::getInstance();
        $getResult = self::getById($id);
        $review = $getResult['data'] ?? null;
        if ($review && $review['image']) {
            $path = UPLOAD_DIR . $review['image'];
            if (file_exists($path)) unlink($path);
        }
        $db->query("DELETE FROM reviews WHERE id = ?", [$id]);

        return new ResponseDto(
            success: true
        );
    }

    public static function getCategories(): ResponseDto
    {
        $db = Database::getInstance();
        return new ResponseDto(
            success: true,
            data: $db->query("SELECT * FROM categories ORDER BY name")->fetchAll()
        );
    }

    public static function getLikesCount(int $reviewId): ResponseDto
    {
        $db = Database::getInstance();
        return  new ResponseDto(
            success: true,
            data: $db->query("SELECT COUNT(*) FROM review_likes WHERE review_id = ?", [$reviewId])->fetchColumn()
        );
    }

    public static function toggleLike(int $userId, int  $reviewId): ResponseDto
    {
        $db = Database::getInstance();
        $exists = $db->query("SELECT id FROM review_likes WHERE user_id = ? AND review_id = ?", [$userId, $reviewId])->fetch();
        if ($exists) {
            $db->query("DELETE FROM review_likes WHERE user_id = ? AND review_id = ?", [$userId, $reviewId]);
            return new ResponseDto(
                success: true,
                data: 'unliked'
            );
        } else {
            $db->query("INSERT INTO review_likes (user_id, review_id) VALUES (?, ?)", [$userId, $reviewId]);
            return new ResponseDto(
                success: true,
                data: 'liked'
            );
        }
    }
}
