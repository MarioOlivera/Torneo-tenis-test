<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Request;
use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Player\ListPlayersUseCase;
use Src\Application\UseCases\Player\CreatePlayerUseCase;
use Src\Application\UseCases\Player\UpdatePlayerUseCase;
use Src\Application\UseCases\Player\ShowPlayerUseCase;
use Src\Application\DTOs\Player\CreatePlayerDTO;
use Src\Application\DTOs\Player\UpdatePlayerDTO;

use Src\Domain\Exceptions\DomainException;

class PlayerController {
    public function __construct(
        private ListPlayersUseCase $listPlayersUseCase,
        private CreatePlayerUseCase $createPlayerUseCase,
        private UpdatePlayerUseCase $updatePlayerUseCase,
        private ShowPlayerUseCase $showPlayerUseCase
    ) {}

    public function index(Request $request) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->listPlayersUseCase->execute()->toArray());
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
            $dto = CreatePlayerDTO::fromRequest($request->getBody());
            $response->setData($this->createPlayerUseCase->execute($dto)->toArray());
            $response->setHttpCode(201);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    public function show(Request $request, int $id): Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->showPlayerUseCase->execute($id)->toArray());
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
            $dto = UpdatePlayerDTO::fromRequest($id, $request->getBody());
            $updatedPlayer = $this->updatePlayerUseCase->execute($dto);
            $response->setData($updatedPlayer->toArray());
            $response->setHttpCode(200);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }
        
        return $response;
    }
}