<?php
declare(strict_types=1);

$membersData = $defaultMembers;

if (!empty($_SESSION['extra_members']) && is_array($_SESSION['extra_members'])) {
    $membersData = array_merge($membersData, $_SESSION['extra_members']);
}

$members = [];

foreach ($membersData as $row) {
    if (!empty($row['is_admin'])) {
        $members[] = new Admin(
            $row['name'],
            $row['email'],
            $row['department'],
            $row['batch'],
            $row['role'],
            $row['achievements'] ?? []
        );
    } else {
        $members[] = new Member(
            $row['name'],
            $row['email'],
            $row['department'],
            $row['batch'],
            $row['role'],
            $row['achievements'] ?? []
        );
    }
}
?>

<section class="section-pad section-soft">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Leadership team</p>
      <h2>Dynamic member list generated with foreach loops and OOP classes.</h2>
    </div>

    <div class="mini-grid">
      <?php foreach ($members as $member): ?>
        <article class="data-card">
          <p class="team-role"><?= Utilities::escape($member->getRole()) ?></p>
          <h3><?= Utilities::escape($member->getName()) ?></h3>
          <p class="team-meta">Department: <?= Utilities::escape($member->getDepartment()) ?></p>
          <p class="team-meta">Batch: <?= Utilities::escape($member->getBatch()) ?></p>
          <p class="team-meta"><?= Utilities::escape($member->getSummary()) ?></p>

          <?php if ($member instanceof Admin): ?>
            <p><span class="tag tag-ok">Admin Privileged Member</span></p>
          <?php else: ?>
            <p><span class="tag tag-muted">General Member</span></p>
          <?php endif; ?>

          <?php $achievements = $member->getAchievements(); ?>
          <?php if (!empty($achievements)): ?>
            <ul class="inline-list">
              <?php foreach ($achievements as $achievement): ?>
                <li><?= Utilities::escape($achievement) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
