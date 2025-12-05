<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Src\MVC\Router;
use Src\MVC\Controllers\HomeController;
use Src\MVC\Controllers\UserController;

$router = new Router();

$router->get('/', HomeController::class, 'index');
$router->get('/about', HomeController::class, 'about');
$router->get('/users', UserController::class, 'index');
$router->get('/users/create', UserController::class, 'create');
$router->get('/users/show', UserController::class, 'show');
$router->post('/users/store', UserController::class, 'store');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($method, $uri);

