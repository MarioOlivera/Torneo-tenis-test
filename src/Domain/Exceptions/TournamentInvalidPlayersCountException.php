<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class TournamentInvalidPlayersCountException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::TOURNAMENT_INVALID_PLAYERS_COUNT, $httpCode);
    }
}
