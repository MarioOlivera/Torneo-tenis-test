<?php
namespace Src\Domain\Enums;

enum TournamentStatus: int {
    case PENDING = 1;
    case COMPLETED = 2;
    case CANCELLED = 3;
    
    
    public function displayName(): string {
        return match ($this) {
            self::PENDING => "Pending",
            self::COMPLETED => "Completed",
            self::CANCELLED => "Cancelled",
        };
    }
    public function isPending() : bool
    {
        return $this == self::PENDING;
    }

    public function isCompleted() : bool
    {
        return $this == self::COMPLETED;
    }

    public function isCancelled() : bool
    {
        return $this == self::CANCELLED;
    }
}