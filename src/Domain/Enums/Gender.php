<?php
namespace Src\Domain\Enums;

enum Gender: int {
    case MALE = 1;
    case FEMALE = 2;

    public function displayName(): string {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
        };
    }

    public function isMale(): bool {
        return $this === self::MALE;
    }

    public function isFemale(): bool {
        return $this === self::FEMALE;
    }

    public static function isValid(int $value): bool {
        return $value === self::MALE->value || $value === self::FEMALE->value;
    }
}