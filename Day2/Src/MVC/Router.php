<?php

declare(strict_types=1);

namespace Src\MVC;

class Router
{
    private array $routes = [];

    private function normalizePath(string $path): string
    {
        $normalized = trim($path, '/');
        return $normalized === '' ? '/' : $normalized;
    }

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'path' => $this->normalizePath($path),
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => 'POST',
            'path' => $this->normalizePath($path),
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        // get path from URI
        $path = parse_url($uri, PHP_URL_PATH);
        $path = $this->normalizePath($path);

        // find matching route
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $this->callController($route['controller'], $route['action']);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function callController(string $controller, string $action): void
    {
        if (!class_exists($controller)) {
            http_response_code(500);
            echo "Controller {$controller} not found";
            return;
        }

        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $action)) {
            http_response_code(500);
            echo "Action {$action} not found";
            return;
        }

        $controllerInstance->$action();
    }
}

