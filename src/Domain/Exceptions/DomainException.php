<?php
namespace Src\Domain\Exceptions;

use Exception;
use Src\Domain\Enums\ErrorCode;

class DomainException extends Exception {
    protected ErrorCode $errorCode;
    protected int $httpCode;

    public function __construct(string $message, ErrorCode $errorCode, int $httpCode = 500) {
        parent::__construct($message, $httpCode);
        $this->errorCode = $errorCode;
        $this->httpCode = $httpCode;
    }

    public function getErrorCode(): ErrorCode {
        return $this->errorCode;
    }

    public function getHttpCode(): int {
        return $this->httpCode;
    }
}
