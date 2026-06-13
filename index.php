<?php
declare(strict_types=1);

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config/config.php';

$page = $_GET['page'] ?? 'home';
$allowedPages = ['home', 'about', 'events', 'members', 'contact', 'admin'];

if (!in_array($page, $allowedPages, true)) {
    $page = 'home';
}

$pageTitles = [
    'home' => SITE_NAME . ' | Home',
    'about' => SITE_NAME . ' | About',
    'events' => SITE_NAME . ' | Events',
    'members' => SITE_NAME . ' | Members',
    'contact' => SITE_NAME . ' | Contact',
    'admin' => SITE_NAME . ' | Admin'
];

$pageTitle = $pageTitles[$page] ?? SITE_NAME;
$currentPage = $page;

require __DIR__ . '/includes/header.php';
require __DIR__ . '/pages/' . $page . '.php';
require __DIR__ . '/includes/footer.php';
