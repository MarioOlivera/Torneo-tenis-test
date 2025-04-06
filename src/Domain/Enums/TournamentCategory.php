<?php
namespace Src\Domain\Enums;

enum TournamentCategory: int {
    case MENS = 1;
    case WOMENS = 2;
    case MIXED = 3;

    public function displayName(): string {
        return match ($this) {
            self::MENS => "Men's Singles",
            self::WOMENS => "Women's Singles",
            self::MIXED => "Mixed Doubles",
        };
    }
    public function isMens(): bool {
        return $this === self::MENS;
    }

    public function isWomens(): bool {
        return $this === self::WOMENS;
    }

    public function isMixed(): bool {
        return $this === self::MIXED;
    }
}