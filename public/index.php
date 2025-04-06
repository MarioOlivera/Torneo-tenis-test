<?php
require __DIR__ . '/../vendor/autoload.php';

$container = new \Src\Infrastructure\Http\Container();

// Registrando las dependencias
$container->bind(
    \Src\Infrastructure\Http\Controllers\PlayerController::class,
    fn() => new \Src\Infrastructure\Http\Controllers\PlayerController(
        $container->resolve(\Src\Application\UseCases\Player\ListPlayersUseCase::class),
        $container->resolve(\Src\Application\UseCases\Player\CreatePlayerUseCase::class),
        $container->resolve(\Src\Application\UseCases\Player\UpdatePlayerUseCase::class)
    )
);

$container->bind(
    \Src\Infrastructure\Http\Controllers\TournamentController::class,
    fn() => new \Src\Infrastructure\Http\Controllers\TournamentController(
        $container->resolve(\Src\Application\UseCases\Tournament\ExecuteTournamentUseCase::class)
    )
);


$container->bind(
    \Src\Application\UseCases\Tournament\ExecuteTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\ExecuteTournamentUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        ),
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        ),
        new \Src\Infrastructure\Persistence\TournamentMatchRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Player\UpdatePlayerUseCase::class,
    fn() => new \Src\Application\UseCases\Player\UpdatePlayerUseCase(
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Player\ListPlayersUseCase::class,
    fn() => new \Src\Application\UseCases\Player\ListPlayersUseCase(
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Player\CreatePlayerUseCase::class,
    fn() => new \Src\Application\UseCases\Player\CreatePlayerUseCase(
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require __DIR__ . '/../src/Infrastructure/Http/Routes/api.php';

// Despachar la ruta
Src\Infrastructure\Http\Router\Router::dispatch($requestMethod, $requestUri, $container);



