<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class ReactionTimeOutOfRangeException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::REACTION_TIME_OUT_OF_RANGE, $httpCode);
    }
}
