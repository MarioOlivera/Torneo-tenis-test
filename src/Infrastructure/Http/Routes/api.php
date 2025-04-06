<?php
namespace Src\Infrastructure\Http\Routes;

use Src\Infrastructure\Http\Controllers\TournamentController;
use Src\Infrastructure\Http\Controllers\PlayerController;
use Src\Infrastructure\Http\Router\Router;

// THE TOURNAMENTS ENDPOINTS
Router::post('/tournaments/{id}/execute', [TournamentController::class, 'execute']);

// THE PLAYERS ENDPOINTS
Router::get('/players', [PlayerController::class, 'index']);
Router::post('/players', [PlayerController::class, 'store']);
Router::get('/players/{id}', [PlayerController::class, 'show']);
Router::patch('/players/{id}', [PlayerController::class, 'update']);