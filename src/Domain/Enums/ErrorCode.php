<?php
namespace Src\Domain\Enums;

enum ErrorCode: int {
    case GENERAL = 1;
    case VALIDATION = 2;
    case TOURNAMENT_NOT_FOUND = 3;
    case TOURNAMENT_IS_NOT_PENDING = 4;
    case TOURNAMENT_NOT_PLAYERS_REGISTERED = 5;
    case TOURNAMENT_INVALID_PLAYERS_COUNT = 6;
    case NOT_FOUND_RESOURCE = 7;

    public function description(): string {
        return match($this) {
            self::GENERAL => "General error",
            self::VALIDATION => "Validation error",
            self::TOURNAMENT_NOT_FOUND => "Tournament not found",
            self::TOURNAMENT_IS_NOT_PENDING => "Tournament is not pending",
            self::TOURNAMENT_NOT_PLAYERS_REGISTERED => "Tournament not players registered",
            self::TOURNAMENT_INVALID_PLAYERS_COUNT => "Tournament invalid players count",
            self::NOT_FOUND_RESOURCE => "Resource not found"
        };
    }
}