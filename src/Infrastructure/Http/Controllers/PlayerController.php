<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Player\ListPlayersUseCase;
use Src\Application\UseCases\Player\CreatePlayerUseCase;
use Src\Application\UseCases\Player\UpdatePlayerUseCase;
use Src\Application\DTOs\Player\CreatePlayerDTO;
use Src\Application\DTOs\Player\UpdatePlayerDTO;

class PlayerController {
    public function __construct(
        private ListPlayersUseCase $listPlayersUseCase,
        private CreatePlayerUseCase $createPlayerUseCase,
        private UpdatePlayerUseCase $updatePlayerUseCase
    ) {}

    public function index() : Envelope {
        $response = new Envelope();
        $response->setData($this->listPlayersUseCase->execute());
        return $response;
    }

    public function store(array $data): Envelope {
        $response = new Envelope();

        try
        {
            $dto = CreatePlayerDTO::fromRequest($data);
            $response->setData($this->createPlayerUseCase->execute($dto)->toArray());
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

    public function update(int $id, array $data): Envelope {
        $response = new Envelope();
        try {
            $id = (int) $id;
            $dto = UpdatePlayerDTO::fromRequest($id, $data);
            $updatedPlayer = $this->updatePlayerUseCase->execute($dto);
            $response->setData($updatedPlayer->toArray());
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
}