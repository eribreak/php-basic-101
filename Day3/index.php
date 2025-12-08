<?php

declare(strict_types=1);

session_start();

use App\Database;

require __DIR__ . '/vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';


$router = require __DIR__ . '/src/routes.php';
$router($uri, $method);

