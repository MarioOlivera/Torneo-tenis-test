<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class ValidationException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::VALIDATION, $httpCode);
    }
}
