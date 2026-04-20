<?php

declare(strict_types=1);

class DashboardController extends BaseController
{
    public function index(): void
    {
        $stats = [
            'customers' => $this->model->count('customers'),
            'leads' => $this->model->count('leads'),
            'projects' => $this->model->count('projects'),
            'tasks' => $this->model->count('tasks'),
            'tickets' => $this->model->count('tickets'),
            'invoices' => $this->model->count('invoices'),
        ];

        $finance = $this->pdo->query("SELECT SUM(CASE WHEN type='income' THEN amount ELSE 0 END) income, SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) expense FROM finance_records")->fetch();

        $recentTasks = $this->pdo->query('SELECT id,title,status,progress FROM tasks ORDER BY id DESC LIMIT 8')->fetchAll();
        $this->view('dashboard/index', compact('stats','finance','recentTasks'));
    }
}
