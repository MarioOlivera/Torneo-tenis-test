<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class SpeedOutOfRangeException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::SPEED_OUT_OF_RANGE, $httpCode);
    }
}
