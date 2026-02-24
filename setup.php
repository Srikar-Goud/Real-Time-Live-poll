<?php
/**
 * Setup Script - Run once to initialize the database
 * Access: http://localhost/poll-platform/setup.php
 * DELETE THIS FILE after setup!
 */

// Prevent re-running accidentally
$lockFile = __DIR__ . '/setup.lock';

require_once 'config/app.php';

$messages = [];
$error = false;

try {
    // Connect without database first
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $messages[] = ['type' => 'success', 'text' => '✅ Database "' . DB_NAME . '" created/verified'];

    $pdo->exec("USE `" . DB_NAME . "`");

    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $messages[] = ['type' => 'success', 'text' => '✅ Table: users'];

    $pdo->exec("CREATE TABLE IF NOT EXISTS polls (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
    )");
    $messages[] = ['type' => 'success', 'text' => '✅ Table: polls'];

    $pdo->exec("CREATE TABLE IF NOT EXISTS poll_options (
        id INT AUTO_INCREMENT PRIMARY KEY,
        poll_id INT NOT NULL,
        option_text VARCHAR(255) NOT NULL,
        display_order INT DEFAULT 0,
        FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE
    )");
    $messages[] = ['type' => 'success', 'text' => '✅ Table: poll_options'];

    $pdo->exec("CREATE TABLE IF NOT EXISTS votes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        poll_id INT NOT NULL,
        option_id INT NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        released_at TIMESTAMP NULL,
        is_active TINYINT(1) DEFAULT 1,
        FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE,
        FOREIGN KEY (option_id) REFERENCES poll_options(id) ON DELETE CASCADE,
        INDEX idx_poll_ip (poll_id, ip_address),
        INDEX idx_active (is_active)
    )");
    $messages[] = ['type' => 'success', 'text' => '✅ Table: votes (with audit columns)'];

    // Seed admin user
    $adminHash = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute(['Admin', 'admin@poll.com', $adminHash]);
    $messages[] = ['type' => 'success', 'text' => '✅ Admin user: admin@poll.com / password'];

    // Seed regular user
    $userHash = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->execute(['Test User', 'user@poll.com', $userHash]);
    $messages[] = ['type' => 'success', 'text' => '✅ Test user: user@poll.com / password'];

    // Seed polls
    $pdo->exec("INSERT IGNORE INTO polls (id, question, status, created_by) VALUES 
        (1, 'What is your favorite programming language?', 'active', 1),
        (2, 'Which framework do you prefer for web development?', 'active', 1),
        (3, 'How many hours do you code per day?', 'inactive', 1)");

    $pdo->exec("INSERT IGNORE INTO poll_options (poll_id, option_text, display_order) VALUES
        (1, 'PHP', 1), (1, 'Python', 2), (1, 'JavaScript', 3), (1, 'Java', 4), (1, 'Go', 5),
        (2, 'Laravel', 1), (2, 'Django', 2), (2, 'React', 3), (2, 'Vue.js', 4),
        (3, '1-2 hours', 1), (3, '3-5 hours', 2), (3, '6-8 hours', 3), (3, '8+ hours', 4)");

    $messages[] = ['type' => 'success', 'text' => '✅ Sample polls and options seeded'];

    // Create lock file
    file_put_contents($lockFile, date('Y-m-d H:i:s'));

} catch (PDOException $e) {
    $messages[] = ['type' => 'danger', 'text' => '❌ Error: ' . $e->getMessage()];
    $error = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup - LivePoll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:600px;">
    <div class="text-center mb-4">
        <h2 class="fw-bold">🗳️ LivePoll Setup</h2>
        <p class="text-muted">Database initialization</p>
    </div>
    <div class="card p-4">
        <?php foreach ($messages as $msg): ?>
        <div class="alert alert-<?= $msg['type'] ?> py-2 mb-2"><?= $msg['text'] ?></div>
        <?php endforeach; ?>

        <?php if (!$error): ?>
        <div class="alert alert-success mt-3">
            <strong>✅ Setup Complete!</strong> Your database is ready.
            <hr>
            <a href="<?= APP_URL ?>/login" class="btn btn-primary">Go to Login →</a>
            <div class="mt-2 small text-muted">⚠️ Please delete <code>setup.php</code> from your server.</div>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
