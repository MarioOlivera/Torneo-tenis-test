<?php
namespace Src\Domain\Exceptions;

final class InvalidPlayerNameException extends \DomainException {
    public function __construct() {
        parent::__construct("Name must contain only letters and spaces.");
    }
}