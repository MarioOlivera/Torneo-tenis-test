<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Player\ListPlayersUseCase;
use Src\Application\UseCases\Player\CreatePlayerUseCase;
use Src\Application\DTOs\Player\CreatePlayerDTO;

class PlayerController {
    public function __construct(
        private ListPlayersUseCase $listPlayersUseCase,
        private CreatePlayerUseCase $createPlayerUseCase
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
            $error = new ErrorResponse(
                $e->getErrorCode(),
                $e->getMessage()
            );
            $response->setErrors($error);
            $response->setHttpCode($e->getHttpCode());
        }

        return $response;
    }
    
}