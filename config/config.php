<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Dhaka');

if (!function_exists('app_is_local_environment')) {
    function app_is_local_environment(): bool
    {
        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));

        if (PHP_SAPI === 'cli') {
            return true;
        }

        return $host === 'localhost'
            || $host === '127.0.0.1'
            || $host === '::1'
            || str_ends_with($host, '.local');
    }
}

if (!function_exists('app_load_env_file')) {
    function app_load_env_file(string $path): void
    {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $envLines = file($path, FILE_IGNORE_NEW_LINES);
        if ($envLines === false) {
            return;
        }

        foreach ($envLines as $envLine) {
            $trimmed = trim($envLine);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            if (str_starts_with($trimmed, 'export ')) {
                $trimmed = trim(substr($trimmed, 7));
            }

            if (!str_contains($trimmed, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $trimmed, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name === '' || preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name) !== 1) {
                continue;
            }

            if ($value !== '') {
                $firstChar = $value[0];
                $lastChar = $value[strlen($value) - 1];

                if (($firstChar === '"' && $lastChar === '"') || ($firstChar === "'" && $lastChar === "'")) {
                    $value = substr($value, 1, -1);
                    if ($firstChar === '"') {
                        $value = str_replace([
                            '\\n',
                            '\\r',
                            '\\t',
                            '\\"',
                            '\\\\',
                        ], [
                            "\n",
                            "\r",
                            "\t",
                            '"',
                            '\\',
                        ], $value);
                    }
                } else {
                    $commentPos = strpos($value, ' #');
                    if ($commentPos !== false) {
                        $value = trim(substr($value, 0, $commentPos));
                    }
                }
            }

            if (getenv($name) === false) {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

if (!function_exists('app_bootstrap_environment')) {
    function app_bootstrap_environment(): void
    {
        $envPaths = [
            dirname(__DIR__) . '/.env',
            getcwd() . '/.env',
            __DIR__ . '/.env',
        ];

        foreach (array_values(array_unique($envPaths)) as $envPath) {
            app_load_env_file($envPath);
        }
    }
}

if (!function_exists('app_env')) {
    function app_env(string $name, ?string $default = null): string
    {
        $value = getenv($name);

        if ($value === false || $value === '') {
            $value = (string) ($_ENV[$name] ?? $_SERVER[$name] ?? '');
        }

        if ($value === '' && $default !== null) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('app_password_matches')) {
    function app_password_matches(string $input, string $configuredPassword): bool
    {
        if ($configuredPassword === '') {
            return false;
        }

        if (preg_match('/^\$(2y|2a|2b|argon2id|argon2i)\$/', $configuredPassword) === 1) {
            return password_verify($input, $configuredPassword);
        }

        return hash_equals($configuredPassword, $input);
    }
}

app_bootstrap_environment();

define('SITE_NAME', 'KUET Math Club');
define('SITE_EMAIL', 'kuetmathclub@kuet.ac.bd');
define('SITE_PHONE', '+8801712345678');
define('SITE_ADDRESS', 'Khulna University of Engineering and Technology, Khulna, Bangladesh');

// Secrets and DB config are loaded from environment variables with local fallbacks.
$adminPassword = app_env('ADMIN_PASSWORD', app_is_local_environment() ? 'KUET-local-admin-2026!' : '');
$dbHost = app_env('DB_HOST', app_is_local_environment() ? '127.0.0.1' : '');
$dbName = app_env('DB_NAME', app_is_local_environment() ? 'kuet_math_club' : '');
$dbUser = app_env('DB_USER', app_is_local_environment() ? 'root' : '');
$dbPass = app_env('DB_PASS', app_is_local_environment() ? '' : '');

if (!defined('USE_DB')) {
    define('USE_DB', filter_var(app_env('USE_DB', 'false'), FILTER_VALIDATE_BOOLEAN));
}

$navItems = [
    'home' => 'Home',
    'about' => 'About',
    'events' => 'Events',
    'members' => 'Members',
    'contact' => 'Contact',
    'admin' => 'Admin'
];

$defaultMembers = [
    [
        'name' => 'Md. Arif Hossain',
        'email' => 'arif@kuet.ac.bd',
        'department' => 'Civil Engineering',
        'batch' => '21',
        'role' => 'President',
        'is_admin' => true,
        'achievements' => ['Olympiad finalist', 'Led weekly proof sessions']
    ],
    [
        'name' => 'Faria Rahman',
        'email' => 'faria@kuet.ac.bd',
        'department' => 'CSE',
        'batch' => '22',
        'role' => 'General Secretary',
        'is_admin' => false,
        'achievements' => ['Organized campus bootcamp', 'Mentored junior teams']
    ],
    [
        'name' => 'Tanvir Hasan',
        'email' => 'tanvir@kuet.ac.bd',
        'department' => 'EEE',
        'batch' => '23',
        'role' => 'Programs Coordinator',
        'is_admin' => false,
        'achievements' => ['Designed mock test pipeline']
    ]
];

$defaultEvents = [
    [
        'title' => 'Orientation and Problem Circle Launch',
        'date' => '2026-01-18',
        'venue' => 'ECE Building Seminar Room, KUET',
        'type' => 'Workshop',
        'description' => 'Kickoff session for new members with guided problem-solving practice and peer introductions.'
    ],
    [
        'title' => 'Proof-Writing Bootcamp',
        'date' => '2026-02-22',
        'venue' => 'Central Classroom Complex, KUET',
        'type' => 'Training',
        'description' => 'Focused training on proof techniques, logical structure, and clear mathematical presentation.'
    ],
    [
        'title' => 'KUET Campus Math Olympiad Final Round',
        'date' => '2026-03-29',
        'venue' => 'Mechanical Engineering Gallery, KUET',
        'type' => 'Competition',
        'description' => 'Final contest round for top-performing campus teams with timed rounds and curated judging.'
    ]
];

spl_autoload_register(static function (string $className): void {
    $file = __DIR__ . '/../classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
