<?php

declare(strict_types=1);

class BaseModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(string $table, int $limit = 20, int $offset = 0, string $search = ''): array
    {
        $allowed = ['customers','leads','projects','tasks','invoices','estimates','contracts','tickets','attendance','leaves','events','notices','finance_records','products','users'];
        if (!in_array($table, $allowed, true)) {
            return [];
        }

        $where = '';
        $params = [];
        if ($search !== '') {
            $where = ' WHERE CONCAT_WS(" ", id, COALESCE(name, ""), COALESCE(title, ""), COALESCE(email, "")) LIKE ?';
            $params[] = "%{$search}%";
        }

        $sql = "SELECT * FROM {$table}{$where} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function insert(string $table, array $data): bool
    {
        $cols = array_keys($data);
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(',', $cols), implode(',', array_fill(0, count($cols), '?')));
        return $this->pdo->prepare($sql)->execute(array_values($data));
    }

    public function update(string $table, int $id, array $data): bool
    {
        $set = implode(',', array_map(fn($k) => "{$k}=?", array_keys($data)));
        $sql = "UPDATE {$table} SET {$set} WHERE id=?";
        $vals = array_values($data);
        $vals[] = $id;
        return $this->pdo->prepare($sql)->execute($vals);
    }

    public function delete(string $table, int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM {$table} WHERE id=?")->execute([$id]);
    }

    public function count(string $table): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    }
}
