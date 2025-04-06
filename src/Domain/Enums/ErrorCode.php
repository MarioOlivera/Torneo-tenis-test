<?php
namespace Src\Domain\Enums;

enum ErrorCode: int {
    case GENERAL = 1;
    case VALIDATION = 2;

    public function description(): string {
        return match($this) {
            self::GENERAL => "Error general",
            self::VALIDATION => "Error de validación"
        };
    }
}