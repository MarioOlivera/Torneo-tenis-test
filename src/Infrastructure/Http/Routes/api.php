<?php
namespace Src\Infrastructure\Http\Routes;

use Src\Infrastructure\Http\Controllers\TournamentController;
use Src\Infrastructure\Http\Controllers\PlayerController;
use Src\Infrastructure\Http\Router\Router;

// THE TOURNAMENTS ENDPOINTS
Router::get('/api/v1/tournaments', [TournamentController::class, 'index']);
Router::post('/api/v1/tournaments', [TournamentController::class, 'store']);
Router::patch('/api/v1/tournaments/{id}', [TournamentController::class, 'update']);
Router::post('/api/v1/tournaments/{id}/cancel', [TournamentController::class, 'cancel']);
Router::post('/api/v1/tournaments/{id}/play', [TournamentController::class, 'play']);
Router::post('/api/v1/tournaments/{id}/registrations', [TournamentController::class, 'registerPlayer']);

// THE PLAYERS ENDPOINTS
Router::get('/api/v1/players', [PlayerController::class, 'index']);
Router::post('/api/v1/players', [PlayerController::class, 'store']);
Router::get('/api/v1/players/{id}', [PlayerController::class, 'show']);
Router::patch('/api/v1/players/{id}', [PlayerController::class, 'update']);