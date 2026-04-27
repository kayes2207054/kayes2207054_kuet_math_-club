<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Dhaka');

// Lightweight .env loader for local development without extra dependencies.
if (file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $envLine) {
        $trimmed = trim($envLine);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $trimmed, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($name !== '' && getenv($name) === false) {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
        }
    }
}

define('SITE_NAME', 'KUET Math Club');
define('SITE_EMAIL', 'kuetmathclub@kuet.ac.bd');
define('SITE_PHONE', '+8801712345678');
define('SITE_ADDRESS', 'Khulna University of Engineering and Technology, Khulna, Bangladesh');

// Secrets and DB config are loaded from environment variables only.
$adminPassword = getenv('ADMIN_PASSWORD') ?: '';
$dbHost = getenv('DB_HOST') ?: '';
$dbName = getenv('DB_NAME') ?: '';
$dbUser = getenv('DB_USER') ?: '';
$dbPass = getenv('DB_PASS') ?: '';
define('USE_DB', filter_var(getenv('USE_DB') ?: 'false', FILTER_VALIDATE_BOOLEAN));

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
        'description' => 'Kickoff event for new members with guided problem-solving demos.'
    ],
    [
        'title' => 'Proof-Writing Bootcamp',
        'date' => '2026-02-22',
        'venue' => 'Central Classroom Complex, KUET',
        'type' => 'Training',
        'description' => 'Focused training on proof techniques, logical structure, and presentation.'
    ],
    [
        'title' => 'KUET Campus Math Olympiad Final Round',
        'date' => '2026-03-29',
        'venue' => 'Mechanical Engineering Gallery, KUET',
        'type' => 'Competition',
        'description' => 'Final contest round for top-performing campus teams.'
    ]
];

spl_autoload_register(static function (string $className): void {
    $file = __DIR__ . '/../classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
