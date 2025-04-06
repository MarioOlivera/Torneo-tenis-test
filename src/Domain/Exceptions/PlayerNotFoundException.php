<?php
namespace Src\Domain\Exceptions;

use Src\Domain\Enums\ErrorCode;

class PlayerNotFoundException extends DomainException {
    public function __construct(string $message, int $httpCode = 400) {
        parent::__construct($message, ErrorCode::PLAYER_NOT_FOUND, $httpCode);
    }
}
