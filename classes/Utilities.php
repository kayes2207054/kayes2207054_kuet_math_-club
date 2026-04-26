<?php
declare(strict_types=1);

interface Displayable
{
    public function getSummary(): string;
}

trait TextFormatterTrait
{
    public function shortText(string $text, int $limit = 90): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit - 3) . '...';
    }
}

final class Utilities
{
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public static function sanitize(string $value): string
    {
        return trim(strip_tags($value));
    }

    public static function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function formatDate(string $ymd): string
    {
        $date = DateTime::createFromFormat('Y-m-d', $ymd);
        return $date ? $date->format('d M Y') : $ymd;
    }
}
