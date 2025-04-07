<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class MalePlayersRequireStrengthAndSpeedException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::MALE_PLAYERS_REQUIRE_STRENGTH_AND_SPEED, $httpCode);
    }
}
