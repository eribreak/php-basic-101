<?php

declare(strict_types=1);

namespace Src\MVC;

class View
{
    private string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = __DIR__ . '/Views/';
    }

    public function render(string $viewName, array $data = []): void
    {
        $viewData = $data;
        $viewFile = $this->viewsPath . $viewName . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: {$viewName}");
        }

        $layoutFile = $this->viewsPath . 'layout.php';
        if (file_exists($layoutFile)) {
            ob_start();
            include $viewFile;          
            $content = ob_get_clean();  

            include $layoutFile;       
        } else {
            include $viewFile;
        }
    }
}

