<?php

declare(strict_types=1);

use App\Controllers\TodoController;

$controller = new TodoController();

return static function (string $uri, string $method) use ($controller): void {
    switch ($uri) {
        case '/':
            $controller->index();
            break;

        case '/create':
            if ($method === 'GET') {
                $controller->create();
            } elseif ($method === 'POST') {
                $controller->store();
            }
            break;

        case '/store':
            if ($method === 'POST') {
                $controller->store();
            } else {
                header('Location: /');
            }
            break;

        case '/edit':
            if ($method === 'GET') {
                $controller->edit();
            } else {
                header('Location: /');
            }
            break;

        case '/update':
            if ($method === 'POST') {
                $controller->update();
            } else {
                header('Location: /');
            }
            break;

        case '/delete':
            if ($method === 'GET') {
                $controller->delete();
            } else {
                header('Location: /');
            }
            break;

        case '/toggle-status':
            if ($method === 'GET') {
                $controller->toggleStatus();
            } else {
                header('Location: /');
            }
            break;

        default:
            http_response_code(404);
            echo '404 - Page not found';
            break;
    }
};


