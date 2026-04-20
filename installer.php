<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/helpers.php';

if (file_exists(__DIR__ . '/config/config.php')) {
    header('Location: /login');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = $_POST['db_pass'] ?? '';
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminName = trim($_POST['admin_name'] ?? 'Administrator');
    $adminPass = $_POST['admin_password'] ?? '';

    try {
        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $sql = file_get_contents(__DIR__ . '/install.sql');
        $pdo->exec($sql);

        $stmt = $pdo->prepare('INSERT INTO users (role_id, name, email, password, status) VALUES (1, ?, ?, ?, 1)');
        $stmt->execute([$adminName, $adminEmail, password_hash($adminPass, PASSWORD_DEFAULT)]);

        $cfg = "<?php\n\nreturn [\n    'app_name' => 'White Label CRM',\n    'base_url' => '',\n    'session_timeout' => 3600,\n    'db' => [\n        'host' => '{$dbHost}',\n        'name' => '{$dbName}',\n        'user' => '{$dbUser}',\n        'pass' => '" . addslashes($dbPass) . "',\n    ],\n];\n";

        file_put_contents(__DIR__ . '/config/config.php', $cfg);
        $success = 'Installation complete. Please login.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRM Installer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width: 720px;">
    <div class="card-body">
      <h3>CRM Installer</h3>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
      <form method="post">
        <h5>Database</h5>
        <div class="row g-2 mb-3">
          <div class="col-md-6"><input class="form-control" name="db_host" placeholder="Host" value="localhost" required></div>
          <div class="col-md-6"><input class="form-control" name="db_name" placeholder="Database" required></div>
          <div class="col-md-6"><input class="form-control" name="db_user" placeholder="User" required></div>
          <div class="col-md-6"><input class="form-control" name="db_pass" placeholder="Password"></div>
        </div>
        <h5>Admin Account</h5>
        <div class="row g-2 mb-3">
          <div class="col-md-6"><input class="form-control" name="admin_name" placeholder="Name" required></div>
          <div class="col-md-6"><input class="form-control" type="email" name="admin_email" placeholder="Email" required></div>
          <div class="col-md-12"><input class="form-control" type="password" name="admin_password" placeholder="Password" required></div>
        </div>
        <button class="btn btn-primary">Install</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
