<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class PlayerAlreadyRegisteredInTournamentException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::PLAYER_ALREADY_REGISTERED_IN_TOURNAMENT, $httpCode);
    }
}
