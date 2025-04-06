<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class TournamentNotPlayersRegisteredException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::TOURNAMENT_NOT_PLAYERS_REGISTERED, $httpCode);
    }
}
