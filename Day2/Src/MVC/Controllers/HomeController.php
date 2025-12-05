<?php

declare(strict_types=1);

namespace Src\MVC\Controllers;

use Src\MVC\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Trang Chủ',
            'message' => 'Mini MVC Framework!',
            'features' => [
                'Router',
                'Controller',
                'View',
                'OOP'
            ]
        ];

        $this->render('home', $data);
    }

    public function about(): void
    {
        $data = [
            'title' => 'About',
            'description' => 'Mini MVC PJ'
        ];

        $this->render('about', $data);
    }
}

