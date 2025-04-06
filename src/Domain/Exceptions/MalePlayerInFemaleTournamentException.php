<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class MalePlayerInFemaleTournamentException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::MALE_PLAYER_IN_FEMALE_TOURNAMENT, $httpCode);
    }
}
