<?php
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $this->pdo = new PDO('sqlite:' . __DIR__ . DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param array<int,mixed> $params
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}
