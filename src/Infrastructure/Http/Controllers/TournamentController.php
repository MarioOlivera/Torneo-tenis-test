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
use Src\Application\DTOs\Tournament\ListTournamentsDTO;

use Src\Domain\Exceptions\DomainException;

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
            $dto = ListTournamentsDTO::fromRequest($request->getQuery());

            $response->setData($this->listTournamentsUseCase->execute($dto)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
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
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
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
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    public function cancel(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->cancelTournamentUseCase->execute($id)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
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
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    public function play(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->playTournamentUseCase->execute($id)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }
}