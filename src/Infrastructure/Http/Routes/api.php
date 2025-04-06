<?php
namespace Src\Infrastructure\Http\Routes;

use Src\Infrastructure\Http\Controllers\PlayerController;
use Src\Infrastructure\Http\Router\Router;

Router::get('/players', [PlayerController::class, 'index']);
Router::post('/players', [PlayerController::class, 'store']);
Router::get('/players/{id}', [PlayerController::class, 'show']);