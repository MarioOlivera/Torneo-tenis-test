<?php
namespace Src\Infrastructure\Http\Routes;

use Src\Infrastructure\Http\Controllers\TournamentController;
use Src\Infrastructure\Http\Controllers\PlayerController;
use Src\Infrastructure\Http\Router\Router;

// THE TOURNAMENTS ENDPOINTS
Router::get('/tournaments', [TournamentController::class, 'index']);
Router::post('/tournaments', [TournamentController::class, 'store']);
Router::patch('/tournaments/{id}', [TournamentController::class, 'update']);
Router::post('/tournaments/{id}/cancel', [TournamentController::class, 'cancel']);
Router::post('/tournaments/{id}/play', [TournamentController::class, 'play']);
Router::post('/tournaments/{id}/registrations', [TournamentController::class, 'registerPlayer']);

// THE PLAYERS ENDPOINTS
Router::get('/players', [PlayerController::class, 'index']);
Router::post('/players', [PlayerController::class, 'store']);
Router::get('/players/{id}', [PlayerController::class, 'show']);
Router::patch('/players/{id}', [PlayerController::class, 'update']);