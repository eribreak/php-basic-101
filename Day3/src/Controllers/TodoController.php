<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TodoModel;

class TodoController
{
    private TodoModel $todoModel;

    public function __construct()
    {
        $this->todoModel = new TodoModel();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? null;
        $todos = $this->todoModel->getAll($status);

        $this->render('index', [
            'todos' => $todos,
            'currentStatus' => $status,
        ]);
    }

    public function create(): void
    {
        $categories = $this->todoModel->getAllCategories();
        $this->render('create', ['categories' => $categories]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
            'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
        ];

        if (empty($data['title'])) {
            $_SESSION['error'] = 'Title không được để trống!';
            header('Location: /create');
            exit;
        }

        try {
            $this->todoModel->create($data);
            $_SESSION['success'] = 'Tạo todo thành công!';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: /create');
            exit;
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $todo = $this->todoModel->getById($id);

        if (!$todo) {
            $_SESSION['error'] = 'Todo không tồn tại!';
            header('Location: /');
            exit;
        }

        $categories = $this->todoModel->getAllCategories();
        $this->render('edit', [
            'todo' => $todo,
            'categories' => $categories,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
            'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
        ];

        if (empty($data['title'])) {
            $_SESSION['error'] = 'Title không được để trống!';
            header("Location: /edit?id={$id}");
            exit;
        }

        try {
            $this->todoModel->update($id, $data);
            $_SESSION['success'] = 'Cập nhật todo thành công!';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header("Location: /edit?id={$id}");
            exit;
        }
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->todoModel->delete($id);
            $_SESSION['success'] = 'Xóa todo thành công!';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: /');
        exit;
    }

    public function toggleStatus(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $todo = $this->todoModel->getById($id);

        if (!$todo) {
            $_SESSION['error'] = 'Todo không tồn tại!';
            header('Location: /');
            exit;
        }

        $newStatus = $todo['status'] === 'completed' ? 'pending' : 'completed';

        try {
            $this->todoModel->update($id, ['status' => $newStatus]);
            $_SESSION['success'] = 'Đổi trạng thái thành công!';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: /');
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        require $viewFile;
    }
}

