<?php
$bg = '';
if (file_exists(__DIR__ . '/../../../config/config.php')) {
    $config = require __DIR__ . '/../../../config/config.php';
    require_once __DIR__ . '/../../../config/database.php';
    $bg = app_setting($pdo, 'login_background', '');
    $logo = app_setting($pdo, 'logo', '');
    $brand = app_setting($pdo, 'company_name', 'White Label CRM');
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Login</title></head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:url('<?= e($bg) ?>') center/cover no-repeat #f3f3f3;">
<div class="card p-4" style="width:360px;">
  <div class="text-center mb-3"><?php if(!empty($logo)):?><img src="<?= e($logo) ?>" height="60"><?php endif; ?><h5><?= e($brand ?? 'CRM') ?></h5></div>
  <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
  <form method="post" action="/login">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input class="form-control mb-2" name="email" type="email" required placeholder="Email">
    <input class="form-control mb-2" name="password" type="password" required placeholder="Password">
    <button class="btn btn-primary w-100">Login</button>
  </form>
</div>
</body></html>
