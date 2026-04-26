<?php
declare(strict_types=1);

if (!isset($_SESSION['is_admin_logged_in'])) {
    $_SESSION['is_admin_logged_in'] = false;
}

$adminError = '';
$adminSuccess = '';

if (($_POST['admin_action'] ?? '') === 'login') {
    $password = Utilities::sanitize($_POST['password'] ?? '');
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['is_admin_logged_in'] = true;
        $adminSuccess = 'Admin login successful.';
    } else {
        $adminError = 'Invalid admin password.';
    }
}

if (($_POST['admin_action'] ?? '') === 'logout') {
    $_SESSION['is_admin_logged_in'] = false;
    $adminSuccess = 'Admin logged out successfully.';
}

if ($_SESSION['is_admin_logged_in'] === true) {
    if (($_POST['admin_action'] ?? '') === 'add_member') {
        $name = Utilities::sanitize($_POST['name'] ?? '');
        $email = Utilities::sanitize($_POST['email'] ?? '');
        $department = Utilities::sanitize($_POST['department'] ?? '');
        $batch = Utilities::sanitize($_POST['batch'] ?? '');
        $role = Utilities::sanitize($_POST['role'] ?? 'Member');

        if ($name !== '' && Utilities::validEmail($email) && $department !== '' && $batch !== '') {
            $conn = Database::getConnection();
            if ($conn instanceof PDO) {
                $stmt = $conn->prepare('INSERT INTO members (name, email, department, batch, role, is_admin) VALUES (:name, :email, :department, :batch, :role, 0)');
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':department' => $department,
                    ':batch' => $batch,
                    ':role' => $role
                ]);
            } else {
                $_SESSION['extra_members'][] = [
                    'name' => $name,
                    'email' => $email,
                    'department' => $department,
                    'batch' => $batch,
                    'role' => $role,
                    'is_admin' => false,
                    'achievements' => []
                ];
            }

            $adminSuccess = 'Member added successfully.';
        } else {
            $adminError = 'Please provide valid member data.';
        }
    }

    if (($_POST['admin_action'] ?? '') === 'add_event') {
        $title = Utilities::sanitize($_POST['title'] ?? '');
        $date = Utilities::sanitize($_POST['date'] ?? '');
        $venue = Utilities::sanitize($_POST['venue'] ?? '');
        $type = Utilities::sanitize($_POST['type'] ?? 'General');
        $description = Utilities::sanitize($_POST['description'] ?? '');

        if ($title !== '' && $date !== '' && $venue !== '' && $description !== '') {
            $conn = Database::getConnection();
            if ($conn instanceof PDO) {
                $stmt = $conn->prepare('INSERT INTO events (title, event_date, venue, type, description) VALUES (:title, :event_date, :venue, :type, :description)');
                $stmt->execute([
                    ':title' => $title,
                    ':event_date' => $date,
                    ':venue' => $venue,
                    ':type' => $type,
                    ':description' => $description
                ]);
            } else {
                $_SESSION['extra_events'][] = [
                    'title' => $title,
                    'date' => $date,
                    'venue' => $venue,
                    'type' => $type,
                    'description' => $description
                ];
            }

            $adminSuccess = 'Event added successfully.';
        } else {
            $adminError = 'Please provide valid event data.';
        }
    }
}
?>

<section class="section-pad section-soft">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Bonus admin panel</p>
      <h2>Manage members and events (session storage or MySQL).</h2>
    </div>

    <?php if ($adminSuccess !== ''): ?>
      <p class="notice notice-success"><?= Utilities::escape($adminSuccess) ?></p>
    <?php endif; ?>

    <?php if ($adminError !== ''): ?>
      <p class="notice notice-error"><?= Utilities::escape($adminError) ?></p>
    <?php endif; ?>

    <?php if ($_SESSION['is_admin_logged_in'] !== true): ?>
      <form class="contact-form max-w" method="post" action="index.php?page=admin">
        <input type="hidden" name="admin_action" value="login">
        <label>
          <span>Admin password</span>
          <input type="password" name="password" required>
        </label>
        <button class="btn btn-primary" type="submit">Login</button>
      </form>
    <?php else: ?>
      <form method="post" action="index.php?page=admin" class="logout-form">
        <input type="hidden" name="admin_action" value="logout">
        <button class="btn btn-ghost" type="submit">Logout</button>
      </form>

      <div class="admin-grid">
        <form class="contact-form" method="post" action="index.php?page=admin">
          <input type="hidden" name="admin_action" value="add_member">
          <h3>Add Member</h3>
          <label><span>Name</span><input name="name" required></label>
          <label><span>Email</span><input name="email" type="email" required></label>
          <label><span>Department</span><input name="department" required></label>
          <label><span>Batch</span><input name="batch" required></label>
          <label><span>Role</span><input name="role" value="Member" required></label>
          <button class="btn btn-primary" type="submit">Add Member</button>
        </form>

        <form class="contact-form" method="post" action="index.php?page=admin">
          <input type="hidden" name="admin_action" value="add_event">
          <h3>Add Event</h3>
          <label><span>Title</span><input name="title" required></label>
          <label><span>Date</span><input name="date" type="date" required></label>
          <label><span>Venue</span><input name="venue" required></label>
          <label><span>Type</span><input name="type" value="General"></label>
          <label><span>Description</span><textarea name="description" rows="4" required></textarea></label>
          <button class="btn btn-primary" type="submit">Add Event</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</section>
