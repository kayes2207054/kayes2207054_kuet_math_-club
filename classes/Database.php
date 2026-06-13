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

        $dbHost = app_env('DB_HOST', app_is_local_environment() ? '127.0.0.1' : '');
        $dbName = app_env('DB_NAME', app_is_local_environment() ? 'kuet_math_club' : '');
        $dbUser = app_env('DB_USER', app_is_local_environment() ? 'root' : '');
        $dbPass = app_env('DB_PASS', app_is_local_environment() ? '' : '');

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
