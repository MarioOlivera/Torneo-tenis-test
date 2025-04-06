<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class FemalePlayerInMaleTournamentException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::FEMALE_PLAYER_IN_MALE_TOURNAMENT, $httpCode);
    }
}
