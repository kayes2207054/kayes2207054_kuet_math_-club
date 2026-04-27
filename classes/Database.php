<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $conn = null;

    public static function getConnection(): ?PDO
    {
        if (!USE_DB) {
            return null;
        }

        if (self::$conn instanceof PDO) {
            return self::$conn;
        }

        $dbHost = getenv('DB_HOST') ?: '';
        $dbName = getenv('DB_NAME') ?: '';
        $dbUser = getenv('DB_USER') ?: '';
        $dbPass = getenv('DB_PASS') ?: '';

        if ($dbHost === '' || $dbName === '' || $dbUser === '') {
            return null;
        }

        $dsn = 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8mb4';

        try {
            self::$conn = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $exception) {
            self::$conn = null;
        }

        return self::$conn;
    }
}
