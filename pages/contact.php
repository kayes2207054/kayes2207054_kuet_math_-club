<?php
declare(strict_types=1);

$name = '';
$email = '';
$message = '';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Utilities::sanitize($_POST['name'] ?? '');
    $email = Utilities::sanitize($_POST['email'] ?? '');
    $message = Utilities::sanitize($_POST['message'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors['name'] = 'Please enter a valid full name.';
    }

    if ($email === '' || !Utilities::validEmail($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($message === '' || mb_strlen($message) < 10) {
        $errors['message'] = 'Message should be at least 10 characters long.';
    }

    if (empty($errors)) {
        $conn = Database::getConnection();

        if ($conn instanceof PDO) {
            $stmt = $conn->prepare('INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (:name, :email, :message, NOW())');
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);
        } else {
            $_SESSION['contact_submissions'][] = [
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'submitted_at' => date('Y-m-d H:i:s')
            ];
        }

        $success = 'Thanks. Your application was submitted successfully.';
        $name = '';
        $email = '';
        $message = '';
    }
}
?>

<section class="section-pad contact-bg">
  <div class="container contact-grid">
    <div>
      <div class="section-head">
        <p class="eyebrow">Get involved</p>
        <h2>Become a member of KUET Math Club.</h2>
      </div>

      <?php if ($success !== ''): ?>
        <p class="notice notice-success"><?= Utilities::escape($success) ?></p>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <p class="notice notice-error">Please fix the highlighted fields and submit again.</p>
      <?php endif; ?>

      <form class="contact-form" method="post" action="index.php?page=contact" novalidate>
        <label>
          <span>Full name</span>
          <input type="text" name="name" value="<?= Utilities::escape($name) ?>" required>
          <small class="field-error"><?= Utilities::escape($errors['name'] ?? '') ?></small>
        </label>
        <label>
          <span>University email</span>
          <input type="email" name="email" value="<?= Utilities::escape($email) ?>" required>
          <small class="field-error"><?= Utilities::escape($errors['email'] ?? '') ?></small>
        </label>
        <label>
          <span>Why do you want to join?</span>
          <textarea name="message" rows="5" required><?= Utilities::escape($message) ?></textarea>
          <small class="field-error"><?= Utilities::escape($errors['message'] ?? '') ?></small>
        </label>

        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Submit Application</button>
          <a class="btn btn-ghost" href="mailto:<?= Utilities::escape(SITE_EMAIL) ?>">Email Directly</a>
        </div>
        <p class="note">Response time: usually within 24 to 48 hours.</p>
      </form>
    </div>

    <aside class="contact-info panel">
      <h3>Contact details</h3>
      <p><strong>Email:</strong> <a href="mailto:<?= Utilities::escape(SITE_EMAIL) ?>"><?= Utilities::escape(SITE_EMAIL) ?></a></p>
      <p><strong>Meeting day:</strong> Thursday, 4:00 PM</p>
      <p><strong>Campus location:</strong> KUET Academic Zone, room announced weekly</p>
      <p><strong>Membership coordinator:</strong> <?= Utilities::escape(SITE_PHONE) ?></p>

      <div class="socials" aria-label="Social links">
        <a href="https://www.facebook.com/kuetmathclub" target="_blank" rel="noopener noreferrer">Facebook</a>
        <a href="https://www.linkedin.com/company/kuet-math-club" target="_blank" rel="noopener noreferrer">LinkedIn</a>
      </div>
    </aside>
  </div>
</section>
