<?php

declare(strict_types=1);

class ResourceController extends BaseController
{
    public function index(string $table): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = trim($_GET['q'] ?? '');
        $rows = $this->model->all($table, $limit, $offset, $search);
        $this->view('search/resource', compact('rows','table','search','page'));
    }

    public function store(): void
    {
        if (!csrf_validate($_POST['_csrf'] ?? null)) die('CSRF invalid');
        $table = $_POST['table'] ?? '';
        $payload = json_decode($_POST['payload'] ?? '{}', true) ?: [];
        $payload['created_at'] = date('Y-m-d H:i:s');
        $ok = $this->model->insert($table, $payload);
        if ($ok && $table === 'invoices') {
            $this->pdo->prepare("INSERT INTO finance_records(type,reference_type,reference_id,description,amount,created_at) VALUES('income','invoice',LAST_INSERT_ID(),'Auto from invoice',?,NOW())")
                ->execute([(float)($payload['total'] ?? 0)]);
        }
        $this->log('create', $table);
        echo json_encode(['ok' => $ok]);
    }

    public function update(): void
    {
        if (!csrf_validate($_POST['_csrf'] ?? null)) die('CSRF invalid');
        $table = $_POST['table'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        $payload = json_decode($_POST['payload'] ?? '{}', true) ?: [];
        $ok = $this->model->update($table, $id, $payload);
        $this->log('update', $table);
        echo json_encode(['ok' => $ok]);
    }

    public function delete(): void
    {
        if (!csrf_validate($_POST['_csrf'] ?? null)) die('CSRF invalid');
        $table = $_POST['table'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        $ok = $this->model->delete($table, $id);
        $this->log('delete', $table);
        echo json_encode(['ok' => $ok]);
    }

    public function convertLead(): void
    {
        $id = (int)($_POST['lead_id'] ?? 0);
        $lead = $this->pdo->prepare('SELECT * FROM leads WHERE id=?');
        $lead->execute([$id]);
        $item = $lead->fetch();
        if ($item) {
            $this->model->insert('customers', [
                'name' => $item['name'], 'email' => $item['email'], 'phone' => $item['phone'], 'company' => $item['company'], 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')
            ]);
            $this->model->update('leads', $id, ['status' => 'converted']);
            $this->log('convert', 'leads');
        }
        $this->redirect('/leads');
    }

    public function clock(): void
    {
        $uid = (int)$_SESSION['user']['id'];
        $action = $_POST['action'] ?? 'in';
        if ($action === 'in') {
            $this->model->insert('attendance', ['user_id' => $uid, 'clock_in' => date('Y-m-d H:i:s'), 'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '', 'created_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->pdo->prepare('UPDATE attendance SET clock_out=NOW() WHERE user_id=? AND DATE(clock_in)=CURDATE() ORDER BY id DESC LIMIT 1')->execute([$uid]);
        }
        $this->redirect('/attendance');
    }

    public function messages(): void
    {
        $uid = (int)$_SESSION['user']['id'];
        $role = (int)$_SESSION['user']['role_id'];
        $sql = $role === 1 ? 'SELECT * FROM messages ORDER BY id DESC LIMIT 100' : 'SELECT * FROM messages WHERE sender_id=? OR receiver_id=? OR is_group=1 ORDER BY id DESC LIMIT 100';
        $stmt = $this->pdo->prepare($sql);
        $role === 1 ? $stmt->execute() : $stmt->execute([$uid, $uid]);
        $rows = $stmt->fetchAll();
        $this->view('messages/index', compact('rows'));
    }

    public function sendMessage(): void
    {
        $uid = (int)$_SESSION['user']['id'];
        $this->model->insert('messages', [
            'sender_id' => $uid,
            'receiver_id' => (int)($_POST['receiver_id'] ?? 0),
            'message' => trim($_POST['message'] ?? ''),
            'is_group' => (int)($_POST['is_group'] ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->model->insert('notifications', [
            'user_id' => (int)($_POST['receiver_id'] ?? 0),
            'type' => 'message',
            'message' => 'New chat message',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->redirect('/messages');
    }

    public function reports(): void
    {
        $tasks = $this->pdo->query('SELECT status, COUNT(*) total FROM tasks GROUP BY status')->fetchAll();
        $finance = $this->pdo->query("SELECT type, SUM(amount) amount FROM finance_records GROUP BY type")->fetchAll();
        $timeLogs = $this->pdo->query('SELECT user_id, SUM(hours) hours FROM task_time_logs GROUP BY user_id')->fetchAll();
        $this->view('reports/index', compact('tasks','finance','timeLogs'));
    }

    public function branding(): void
    {
        $this->view('settings/branding');
    }

    public function saveBranding(): void
    {
        if (!csrf_validate($_POST['_csrf'] ?? null)) die('CSRF invalid');
        $fields = ['company_name','header_text','footer_text','email_brand_name'];
        foreach ($fields as $f) {
            $this->pdo->prepare('REPLACE INTO settings (setting_key,setting_value) VALUES (?,?)')->execute([$f, trim($_POST[$f] ?? '')]);
        }

        foreach (['logo','favicon','login_background','email_logo'] as $fileKey) {
            if (!empty($_FILES[$fileKey]['name'])) {
                $name = time() . '_' . basename($_FILES[$fileKey]['name']);
                $target = __DIR__ . '/../../uploads/branding/' . $name;
                move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target);
                $this->pdo->prepare('REPLACE INTO settings (setting_key,setting_value) VALUES (?,?)')->execute([$fileKey, '/uploads/branding/' . $name]);
            }
        }

        $this->log('update', 'branding');
        $this->redirect('/settings/branding');
    }

    public function search(): void
    {
        $q = '%' . trim($_GET['q'] ?? '') . '%';
        $tables = ['customers','leads','projects','tasks','tickets','invoices'];
        $results = [];
        foreach ($tables as $t) {
            $stmt = $this->pdo->prepare("SELECT id, COALESCE(name,title,email) label FROM {$t} WHERE CONCAT_WS(' ',id,COALESCE(name,''),COALESCE(title,''),COALESCE(email,'')) LIKE ? LIMIT 5");
            $stmt->execute([$q]);
            $results[$t] = $stmt->fetchAll();
        }
        $this->view('search/index', compact('results'));
    }
}
