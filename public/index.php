<?php
require __DIR__ . '/../src/Infrastructure/Config/container.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require __DIR__ . '/../src/Infrastructure/Http/Routes/api.php';

// Despachar la ruta
Src\Infrastructure\Http\Router\Router::dispatch($requestMethod, $requestUri, $container);



