<?php

declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_validate(?string $token): bool
{
    return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], (string) $token);
}

function is_logged_in(): bool
{
    if (empty($_SESSION['user'])) {
        return false;
    }

    $timeout = $_SESSION['timeout'] ?? 0;
    if (time() - $timeout > 3600) {
        session_destroy();
        return false;
    }

    $_SESSION['timeout'] = time();
    return true;
}

function app_setting(PDO $pdo, string $key, ?string $default = null): ?string
{
    static $cache = [];
    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
    $stmt->execute([$key]);
    $val = $stmt->fetchColumn();
    $cache[$key] = $val !== false ? (string) $val : $default;
    return $cache[$key];
}


function app_base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
    $base = ($dir === '.' || $dir === '/') ? '' : $dir;
    return $base;
}

function app_url(string $path = '/'): string
{
    $base = app_base_path();
    $clean = '/' . ltrim($path, '/');
    if ($clean === '//') {
        $clean = '/';
    }

    return $base . ($clean === '/' ? '' : $clean);
}

