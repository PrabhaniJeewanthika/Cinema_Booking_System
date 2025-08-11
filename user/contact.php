<?php
require_once __DIR__ . '/../config/db.php'; 

$success = false;
$errors  = [];

// ✅ Ensure $pdo is a real PDO handle (no other code changed)
try {
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=cinema_booking;charset=utf8mb4',
            'root',
            ''
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
} catch (Throwable $e) {
    $errors[] = 'Database connection failed.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '')                               $errors[] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if ($subject === '')                            $errors[] = 'Please enter a subject.';
    if (strlen($message) < 5)                       $errors[] = 'Message is too short.';

    if (!$errors) {
        try {
            // Collect meta
            $ip         = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent  = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

            // Save
            $stmt = $pdo->prepare(
                "INSERT INTO contact_messages (name, email, phone, subject, message, ip, user_agent)
                 VALUES (:name, :email, :phone, :subject, :message, :ip, :ua)"
            );
            $stmt->execute([
                ':name'    => $name,
                ':email'   => $email,
                ':phone'   => $phone !== '' ? $phone : null,
                ':subject' => $subject,
                ':message' => $message,
                ':ip'      => $ip,
                ':ua'      => $userAgent,
            ]);

            $success = true;
            // clear fields after successful save
            $name = $email = $phone = $subject = $message = '';
        } catch (Throwable $e) {
            $errors[] = 'Could not save your message. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Contact | Cinema Hall</title>
<style>
  :root{
    --red:#e50914; --bg:#000; --panel:#0f0f0f; --line:#262626; --muted:#b5b5b5; --text:#e9e9e9;
  }
  * { box-sizing: border-box; }
  html, body { height: 100%; }
  body {
    margin:0; padding:0; background:var(--bg); color:var(--text);
    font-family: Arial, Helvetica, sans-serif;
    display:flex; flex-direction:column; min-height:100vh; /* sticky footer pattern */
  }

  .page {
    flex:1 0 auto;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 28px 16px 40px;
  }

  .brand { color: var(--red); font-weight:800; letter-spacing:.3px; margin:0 0 6px; }
  .muted { color: var(--muted); font-size:14px; margin-bottom:18px; }

  .grid {
    display:grid;
    grid-template-columns: 1.1fr .9fr;
    gap:20px;
  }
  @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }

  .card {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 18px;
  }

  .title {
    margin:0 0 12px; font-size:18px; color:#fff;
    border-left:4px solid var(--red); padding-left:10px;
  }

  label { display:block; margin:10px 0 6px; font-size:14px; color:#e5e5e5; }
  input, textarea {
    width:100%; padding:12px; border-radius:12px;
    border:1px solid #202020; background:#0b0b0b; color:#eee; outline:none;
  }
  textarea { min-height:120px; resize:vertical; }

  .row { display:flex; gap:12px; }
  .row > div { flex:1; }
  @media (max-width: 600px) { .row { flex-direction:column; } }

  .btn {
    border:none; background:var(--red); color:#fff;
    padding:12px 16px; border-radius:12px; font-weight:800;
    cursor:pointer; width:100%; margin-top:10px;
  }

  .alert { padding:12px 14px; border-radius:12px; margin-bottom:12px; font-size:14px; }
  .alert.ok { background:#0d1a13; border:1px solid #1f3b2d; color:#c9ffe0; }
  .alert.err { background:#1a0d0d; border:1px solid #3b1f1f; color:#ffd1d1; }

  .info { list-style:none; padding:0; margin:0; }
  .info li { padding:10px 0; border-bottom:1px dashed #232323; color:#d0d0d0; }

  /* Footer area (from footer.php) will sit at bottom thanks to flex) */
</style>
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="page">
  <h1 class="brand">🎬 Cinema Hall — Contact Us</h1>
  <p class="muted">Questions about bookings, showtimes, or facilities? Send us a message and we’ll get back to you.</p>

  <div class="grid">
    <!-- Contact Form -->
    <section class="card">
      <h2 class="title">Send a message</h2>

      <?php if (!empty($errors)): ?>
        <div class="alert err">
          <?php foreach ($errors as $e): ?>• <?php echo htmlspecialchars($e); ?><br><?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert ok">✅ Thanks! Your message has been received. We’ll reply shortly.</div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="row">
          <div>
            <label for="name">Your Name</label>
            <input id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
          </div>
          <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
          </div>
        </div>

        <div class="row">
          <div>
            <label for="phone">Phone (optional)</label>
            <input id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
          </div>
          <div>
            <label for="subject">Subject</label>
            <input id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
          </div>
        </div>

        <label for="message">Message</label>
        <textarea id="message" name="message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>

        <button class="btn" type="submit">Send Message</button>
      </form>
    </section>

    <!-- Contact Info -->
    <aside class="card">
      <h2 class="title">Our details</h2>
      <ul class="info">
        <li>📍 123 Movie Street, Colombo 07, Sri Lanka</li>
        <li>📞 +94 77 123 4567</li>
        <li>✉️ info@cinemabooking.lk</li>
        <li>🕒 9:00 AM – 10:00 PM (Daily)</li>
      </ul>
      <p class="muted" style="margin-top:8px;">
        Parking available • Wheelchair accessible • Dolby Atmos auditorium
      </p>
    </aside>
  </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
