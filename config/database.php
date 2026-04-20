<?php

declare(strict_types=1);

if (!isset($config) || !is_array($config) || !isset($config['db'])) {
    throw new RuntimeException('Missing application configuration. Run installer.php or verify config/config.php.');
}

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['db']['host'] ?? '', $config['db']['name'] ?? '');
$pdo = new PDO($dsn, $config['db']['user'] ?? '', $config['db']['pass'] ?? '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
