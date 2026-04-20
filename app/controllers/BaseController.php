<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/BaseModel.php';

class BaseController
{
    protected BaseModel $model;

    public function __construct(protected PDO $pdo, protected array $config)
    {
        $this->model = new BaseModel($pdo);
    }

    protected function view(string $file, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/' . $file . '.php';
        include __DIR__ . '/../views/layouts/footer.php';
    }

    protected function log(string $action, string $module): void
    {
        $uid = (int)($_SESSION['user']['id'] ?? 0);
        $stmt = $this->pdo->prepare('INSERT INTO activity_logs (user_id,module,action,ip_address) VALUES (?,?,?,?)');
        $stmt->execute([$uid, $module, $action, $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . app_url($path));
        exit;
    }
}
