<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

define('VIEW_PATH', __DIR__ . '/../src/Infrastructure/Views/');
define('PATH', __DIR__ . '/../src');

if (isset($_ENV['APP_ENV']) && ($_ENV['APP_ENV'] === 'development' || $_ENV['APP_ENV'] === 'testing')) 
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else 
{
    ini_set('display_errors', 0);
    error_reporting(0);
}

$container = new \Src\Infrastructure\Http\Container();

// Registrando las dependencias
$container->bind(
    \Src\Infrastructure\Http\Controllers\DocumentationController::class,
    fn() => new \Src\Infrastructure\Http\Controllers\DocumentationController()
);

$container->bind(
    \Src\Infrastructure\Http\Controllers\PlayerController::class,
    fn() => new \Src\Infrastructure\Http\Controllers\PlayerController(
        $container->resolve(\Src\Application\UseCases\Player\ListPlayersUseCase::class),
        $container->resolve(\Src\Application\UseCases\Player\CreatePlayerUseCase::class),
        $container->resolve(\Src\Application\UseCases\Player\UpdatePlayerUseCase::class),
        $container->resolve(\Src\Application\UseCases\Player\ShowPlayerUseCase::class)
    )
);

$container->bind(
    \Src\Infrastructure\Http\Controllers\TournamentController::class,
    fn() => new \Src\Infrastructure\Http\Controllers\TournamentController(
        $container->resolve(\Src\Application\UseCases\Tournament\ListTournamentsUseCase::class),
        $container->resolve(\Src\Application\UseCases\Tournament\CreateTournamentUseCase::class),
        $container->resolve(\Src\Application\UseCases\Tournament\UpdateTournamentUseCase::class),
        $container->resolve(\Src\Application\UseCases\Tournament\CancelTournamentUseCase::class),
        $container->resolve(\Src\Application\UseCases\Tournament\PlayTournamentUseCase::class),
        $container->resolve(Src\Application\UseCases\Tournament\RegisterPlayerToTournamentUseCase::class)
    )
);

$container->bind(
    \Src\Application\UseCases\Tournament\ListTournamentsUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\ListTournamentsUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Tournament\CreateTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\CreateTournamentUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Tournament\UpdateTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\UpdateTournamentUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Tournament\CancelTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\CancelTournamentUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

$container->bind(
    \Src\Application\UseCases\Tournament\PlayTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\PlayTournamentUseCase(
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
    \Src\Application\UseCases\Tournament\RegisterPlayerToTournamentUseCase::class,
    fn() => new \Src\Application\UseCases\Tournament\RegisterPlayerToTournamentUseCase(
        new \Src\Infrastructure\Persistence\TournamentRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        ),
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        ),
        new \Src\Infrastructure\Persistence\TournamentRegistrationRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);


$container->bind(
    \Src\Application\UseCases\Player\UpdatePlayerUseCase::class,
    fn() => new \Src\Application\UseCases\Player\UpdatePlayerUseCase(
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        ),
        new \Src\Infrastructure\Persistence\TournamentRegistrationRepository(
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

$container->bind(
    \Src\Application\UseCases\Player\ShowPlayerUseCase::class,
    fn() => new \Src\Application\UseCases\Player\ShowPlayerUseCase(
        new \Src\Infrastructure\Persistence\PlayerRepository(
            \Src\Infrastructure\Persistence\MySQLiConnection::getInstance()
        )
    )
);

return $container;



