<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/helpers.php';

$configFile = __DIR__ . '/config/config.php';
if (!file_exists($configFile)) {
    header('Location: ' . app_url('/installer.php'));
    exit;
}

/** @var array $config */
$config = require $configFile;
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/ResourceController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$basePath = app_base_path();
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

$routes = [
    'GET' => [
        '/' => ['DashboardController', 'index', true],
        '/login' => ['AuthController', 'showLogin', false],
        '/logout' => ['AuthController', 'logout', true],
        '/dashboard' => ['DashboardController', 'index', true],
        '/customers' => ['ResourceController', 'index', true, 'customers'],
        '/leads' => ['ResourceController', 'index', true, 'leads'],
        '/projects' => ['ResourceController', 'index', true, 'projects'],
        '/tasks' => ['ResourceController', 'index', true, 'tasks'],
        '/invoices' => ['ResourceController', 'index', true, 'invoices'],
        '/estimates' => ['ResourceController', 'index', true, 'estimates'],
        '/contracts' => ['ResourceController', 'index', true, 'contracts'],
        '/tickets' => ['ResourceController', 'index', true, 'tickets'],
        '/messages' => ['ResourceController', 'messages', true],
        '/attendance' => ['ResourceController', 'index', true, 'attendance'],
        '/leaves' => ['ResourceController', 'index', true, 'leaves'],
        '/events' => ['ResourceController', 'index', true, 'events'],
        '/notices' => ['ResourceController', 'index', true, 'notices'],
        '/finance' => ['ResourceController', 'index', true, 'finance_records'],
        '/products' => ['ResourceController', 'index', true, 'products'],
        '/reports' => ['ResourceController', 'reports', true],
        '/users' => ['ResourceController', 'index', true, 'users'],
        '/settings/branding' => ['ResourceController', 'branding', true],
        '/search' => ['ResourceController', 'search', true],
    ],
    'POST' => [
        '/login' => ['AuthController', 'login', false],
        '/resource/store' => ['ResourceController', 'store', true],
        '/resource/update' => ['ResourceController', 'update', true],
        '/resource/delete' => ['ResourceController', 'delete', true],
        '/leads/convert' => ['ResourceController', 'convertLead', true],
        '/attendance/clock' => ['ResourceController', 'clock', true],
        '/messages/send' => ['ResourceController', 'sendMessage', true],
        '/settings/branding' => ['ResourceController', 'saveBranding', true],
    ],
];

if (!isset($routes[$method][$uri])) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

[$controllerName, $action, $auth] = $routes[$method][$uri];
$args = array_slice($routes[$method][$uri], 3);

if ($auth && !is_logged_in()) {
    header('Location: ' . app_url('/login'));
    exit;
}

$controller = new $controllerName($pdo, $config);
call_user_func_array([$controller, $action], $args);
