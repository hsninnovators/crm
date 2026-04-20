<?php

declare(strict_types=1);

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        if (!csrf_validate($_POST['_csrf'] ?? null)) {
            die('Invalid CSRF token');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? AND status = 1 LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid login credentials';
            $this->redirect('/login');
        }

        $_SESSION['user'] = $user;
        $_SESSION['timeout'] = time();
        $this->log('login', 'auth');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        $this->log('logout', 'auth');
        session_destroy();
        $this->redirect('/login');
    }
}
