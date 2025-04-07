<?php
namespace Src\Domain\Collections;

use Src\Domain\Entities\Tournament;

class TournamentCollection extends BaseCollection {
    public function append($value): void {
        if (!$value instanceof Tournament) {
            throw new \InvalidArgumentException("Value must be an instance of Tournament");
        }
        parent::append($value);
    }
}
