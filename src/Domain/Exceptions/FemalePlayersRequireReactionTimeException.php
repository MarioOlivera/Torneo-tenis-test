<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class FemalePlayersRequireReactionTimeException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::FEMALE_PLAYERS_REQUIRE_REACTION_TIME, $httpCode);
    }
}
