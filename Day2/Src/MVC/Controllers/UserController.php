<?php

declare(strict_types=1);

namespace Src\MVC\Controllers;

use Src\MVC\Controller;
use Src\MVC\Models\UserModel;

class UserController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        // get data from Model
        $users = $this->userModel->all();

        $data = [
            'title' => 'Danh Sách Users',
            'users' => $users
        ];

        $this->render('user/index', $data);
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

        // get user from Model
        $user = $this->userModel->find($id);

        // if user not found
        if ($user === null) {
            $this->redirect('/users');
            return;
        }

        $data = [
            'title' => 'Chi Tiết User',
            'user' => $user
        ];

        $this->render('user/show', $data);
    }

    public function create(): void
    {
        $data = [
            'title' => 'Tạo User Mới'
        ];

        $this->render('user/create', $data);
    }

    public function store(): void
    {
        // get data from form
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($name) || empty($email)) {
            $data = [
                'title' => 'Tạo User Mới',
                'error' => 'Vui lòng điền đầy đủ thông tin',
                'name' => $name,
                'email' => $email
            ];
            $this->render('user/create', $data);
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $data = [
                'title' => 'Tạo User Mới',
                'error' => 'Email đã tồn tại',
                'name' => $name,
                'email' => $email
            ];
            $this->render('user/create', $data);
            return;
        }

        $this->userModel->create([
            'name' => $name,
            'email' => $email
        ]);

        // redirect to list
        $this->redirect('/users');
    }
}

