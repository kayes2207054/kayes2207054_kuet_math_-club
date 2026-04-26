<?php
declare(strict_types=1);

session_start();

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
