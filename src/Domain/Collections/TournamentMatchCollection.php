<?php
namespace Src\Domain\Collections;

use Src\Domain\Entities\TournamentMatch;

class TournamentMatchCollection extends BaseCollection {
    public function append($value): void {
        if (!$value instanceof TournamentMatch) {
            throw new \InvalidArgumentException("Value must be an instance of TournamentMatch");
        }
        parent::append($value);
    }
}
