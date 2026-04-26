<?php
declare(strict_types=1);

/** @var string $pageTitle */
/** @var string $currentPage */
/** @var array<string,string> $navItems */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= Utilities::escape($pageTitle) ?></title>
  <meta name="description" content="KUET Math Club dynamic portfolio using PHP and OOP concepts.">
  <meta name="theme-color" content="#0a2e73">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php?page=home" aria-label="KUET Math Club home">
        <span class="logo-mark" aria-hidden="true">∫</span>
        <span class="logo-text">KUET Math Club</span>
      </a>

      <button class="nav-toggle-btn" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="primary-navigation">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav id="primary-navigation" class="nav" aria-label="Primary">
        <ul class="nav-links">
          <?php foreach ($navItems as $key => $label): ?>
            <li><a class="<?= $currentPage === $key ? 'active-nav' : '' ?>" href="index.php?page=<?= Utilities::escape($key) ?>"><?= Utilities::escape($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <a class="btn btn-nav" href="index.php?page=contact">Join Now</a>
    </div>
  </header>

  <main id="main">
