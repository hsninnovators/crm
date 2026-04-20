<?php
$brandName = app_setting($this->pdo, 'company_name', 'White Label CRM');
$logo = app_setting($this->pdo, 'logo', '');
$favicon = app_setting($this->pdo, 'favicon', '');
$headerText = app_setting($this->pdo, 'header_text', 'CRM');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($brandName) ?></title>
  <?php if ($favicon): ?><link rel="icon" href="<?= e($favicon) ?>"><?php endif; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="<?= e(app_url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= e(app_url('/dashboard')) ?>"><?php if($logo): ?><img src="<?= e($logo) ?>" height="32"><?php else: ?><?= e($brandName) ?><?php endif; ?></a>
    <span class="text-light small"><?= e($headerText) ?></span>
    <form class="d-flex" action="<?= e(app_url('/search')) ?>" method="get">
      <input class="form-control form-control-sm" name="q" placeholder="Global search">
    </form>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <aside class="col-md-2 bg-light min-vh-100 p-2">
      <div class="list-group small">
        <a class="list-group-item" href="<?= e(app_url('/dashboard')) ?>">Dashboard</a><a class="list-group-item" href="<?= e(app_url('/customers')) ?>">Customers</a>
        <a class="list-group-item" href="<?= e(app_url('/leads')) ?>">Leads</a><a class="list-group-item" href="<?= e(app_url('/projects')) ?>">Projects</a><a class="list-group-item" href="<?= e(app_url('/tasks')) ?>">Tasks/Kanban/Gantt</a>
        <a class="list-group-item" href="<?= e(app_url('/invoices')) ?>">Invoices</a><a class="list-group-item" href="<?= e(app_url('/estimates')) ?>">Estimates</a><a class="list-group-item" href="<?= e(app_url('/contracts')) ?>">Contracts</a>
        <a class="list-group-item" href="<?= e(app_url('/tickets')) ?>">Tickets</a><a class="list-group-item" href="<?= e(app_url('/messages')) ?>">Messaging</a><a class="list-group-item" href="<?= e(app_url('/attendance')) ?>">Attendance</a>
        <a class="list-group-item" href="<?= e(app_url('/leaves')) ?>">Leaves</a><a class="list-group-item" href="<?= e(app_url('/events')) ?>">Events</a><a class="list-group-item" href="<?= e(app_url('/finance')) ?>">Finance</a>
        <a class="list-group-item" href="<?= e(app_url('/products')) ?>">Products</a><a class="list-group-item" href="<?= e(app_url('/reports')) ?>">Reports</a><a class="list-group-item" href="<?= e(app_url('/users')) ?>">Users</a>
        <a class="list-group-item" href="<?= e(app_url('/settings/branding')) ?>">Brand Settings</a><a class="list-group-item" href="<?= e(app_url('/logout')) ?>">Logout</a>
      </div>
    </aside>
    <main class="col-md-10 p-3">
