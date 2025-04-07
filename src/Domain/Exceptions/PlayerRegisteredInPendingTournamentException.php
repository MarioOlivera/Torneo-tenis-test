<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class PlayerRegisteredInPendingTournamentException extends DomainException {
    public function __construct(string $message = "Cannot change gender while registered in pending tournaments", int $httpCode = 400) {
        parent::__construct($message, ErrorCode::PLAYER_REGISTERED_IN_PENDING_TOURNAMENT, $httpCode);
    }
}
