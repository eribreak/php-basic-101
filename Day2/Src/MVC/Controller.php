<?php

declare(strict_types=1);

namespace Src\MVC;

abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function render(string $viewName, array $data = []): void
    {
        $this->view->render($viewName, $data);
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}

