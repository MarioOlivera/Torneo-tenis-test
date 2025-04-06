<?php
namespace Src\Domain\Enums;

enum TournamentCategory: int {
    case MENS = 1;
    case WOMENS = 2;

    public function displayName(): string {
        return match ($this) {
            self::MENS => "Men's Singles",
            self::WOMENS => "Women's Singles"
        };
    }
    public function isMens(): bool {
        return $this === self::MENS;
    }

    public function isWomens(): bool {
        return $this === self::WOMENS;
    }

    public static function isValid(int $value): bool {
        return $value === self::MENS->value || $value === self::WOMENS->value;
    }
}