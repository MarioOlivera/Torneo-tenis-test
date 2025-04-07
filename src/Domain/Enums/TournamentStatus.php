<?php
namespace Src\Domain\Enums;

enum TournamentStatus: int {
    case PENDING = 1;
    case PLAYED = 2;
    case CANCELLED = 3;
    
    
    public function displayName(): string {
        return match ($this) {
            self::PENDING => "Pending",
            self::PLAYED => "Played",
            self::CANCELLED => "Cancelled",
        };
    }
    public function isPending() : bool
    {
        return $this == self::PENDING;
    }

    public function isPlayed() : bool
    {
        return $this == self::PLAYED;
    }

    public function isCancelled() : bool
    {
        return $this == self::CANCELLED;
    }
}