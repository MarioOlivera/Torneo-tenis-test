<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Request;
use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Tournament\CreateTournamentUseCase;
use Src\Application\UseCases\Tournament\UpdateTournamentUseCase;
use Src\Application\UseCases\Tournament\ListTournamentsUseCase;
use Src\Application\UseCases\Tournament\CancelTournamentUseCase;
use Src\Application\UseCases\Tournament\PlayTournamentUseCase;
use Src\Application\UseCases\Tournament\RegisterPlayerToTournamentUseCase;
use Src\Application\DTOs\Tournament\CreateTournamentDTO;
use Src\Application\DTOs\Tournament\UpdateTournamentDTO;
use Src\Application\DTOs\Tournament\RegisterPlayerTournamentDTO;
use Src\Application\DTOs\Player\ListPlayersDTO;

class TournamentController {
    public function __construct(
        private ListTournamentsUseCase $listTournamentsUseCase,
        private CreateTournamentUseCase $createTournamentUseCase,
        private UpdateTournamentUseCase $updateTournamentUseCase,
        private CancelTournamentUseCase $cancelTournamentUseCase,
        private PlayTournamentUseCase $playTournamentUseCase,
        private RegisterPlayerToTournamentUseCase $registerPlayerToTournamentUseCase
    ) {}

    public function index(Request $request) : Envelope {
        $response = new Envelope();

        try
        {
            $dto = ListPlayersDTO::fromRequest($request->getQuery());
            $response->setData($this->listTournamentsUseCase->execute($dto));
        }
        catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }
        return $response;
    }

    public function store(Request $request): Envelope {
        $response = new Envelope();

        try
        {
            $dto = CreateTournamentDTO::fromRequest($request->getBody());
            $response->setData($this->createTournamentUseCase->execute($dto)->toArray());
            $response->setHttpCode(201);
        } catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }

        return $response;
    }

    public function update(Request $request, int $id): Envelope {
        $response = new Envelope();
        try {
            $id = (int) $id;
            $dto = UpdateTournamentDTO::fromRequest($id, $request->getBody());
            $updated = $this->updateTournamentUseCase->execute($dto);
            $response->setData($updated->toArray());
            $response->setHttpCode(200);
        } catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }
        return $response;
    }

    public function cancel(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $this->cancelTournamentUseCase->execute($id);
        }
        catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }

        return $response;
    }

    public function registerPlayer(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $dto = RegisterPlayerTournamentDTO::fromRequest($id, $request->getBody());
            $response->setData($this->registerPlayerToTournamentUseCase->execute($dto)->toArray());
        }
        catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }

        return $response;
    }

    public function play(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $this->playTournamentUseCase->execute($id);
            $response->setData(["RECIBI" => $id]);
        }
        catch (\Src\Domain\Exceptions\DomainException $e) {
            $response->setResponse(false);
            $response->setErrors(new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            ));
            $response->setHttpCode($e->getHttpCode());
        }

        return $response;
    }
}