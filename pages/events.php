<?php
declare(strict_types=1);

$eventsData = $defaultEvents;

if (!empty($_SESSION['extra_events']) && is_array($_SESSION['extra_events'])) {
    $eventsData = array_merge($eventsData, $_SESSION['extra_events']);
}

$events = [];

foreach ($eventsData as $row) {
    $events[] = new Event(
        $row['title'],
        $row['date'],
        $row['venue'],
        $row['type'],
        $row['description']
    );
}
?>

<section class="section-pad">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Programs</p>
      <h2>Signature activities that create measurable growth.</h2>
    </div>

    <div class="mini-grid">
      <?php foreach ($events as $event): ?>
        <article class="data-card">
          <h3><?= Utilities::escape($event->getTitle()) ?></h3>
          <p><strong>Date:</strong> <?= Utilities::escape(Utilities::formatDate($event->getDate())) ?></p>
          <p><strong>Venue:</strong> <?= Utilities::escape($event->getVenue()) ?></p>
          <p><strong>Type:</strong> <?= Utilities::escape($event->getType()) ?></p>
          <p><?= Utilities::escape($event->getDescription()) ?></p>

          <?php if ($event->isUpcoming()): ?>
            <p><span class="tag tag-ok">Upcoming</span></p>
          <?php else: ?>
            <p><span class="tag tag-muted">Completed</span></p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
