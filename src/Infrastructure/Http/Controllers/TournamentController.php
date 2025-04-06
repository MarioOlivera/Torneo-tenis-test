<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Tournament\ExecuteTournamentUseCase;

class TournamentController {
    public function __construct(
        private ExecuteTournamentUseCase $executeTournamentUseCase
    ) {}

    public function execute(int $id, array $data) : Envelope {
        $response = new Envelope();

        try
        {
            $this->executeTournamentUseCase->execute($id);
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