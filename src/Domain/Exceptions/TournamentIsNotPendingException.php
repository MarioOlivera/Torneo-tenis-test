<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class TournamentIsNotPendingException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::TOURNAMENT_IS_NOT_PENDING, $httpCode);
    }
}
