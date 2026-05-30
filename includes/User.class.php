<?php

class User
{
    public static function register(string $username, string $email, string $password): ResponseDto
    {
        $db = Database::getInstance();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $db->query(
                "INSERT INTO users (username, email, password) VALUES(?, ?, ?)",
                [$username, $email, $passwordHash]
            );
            return new ResponseDto(
                success: true,
                data: ['id' => $db->lastInsertId()]
            );
        } catch (PDOException $e) {
            return new ResponseDto(
                success: false,
                error: 'Username or email already taken'
            );
        }
    }

    public static function login(string $email, string $password): ResponseDto
    {
        $db = Database::getInstance();
        $user = $db->query("SELECT * FROM users WHERE email = ?", [$email])->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return new ResponseDto(
                success: true,
                data: ['user' => $user]
            );
        }
        return new ResponseDto(
            success: false,
            error: 'Invalid email or password'
        );
    }
}
