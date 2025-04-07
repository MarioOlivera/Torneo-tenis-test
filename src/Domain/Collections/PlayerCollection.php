<?php
namespace Src\Domain\Collections;

use Src\Domain\Entities\Player;

class PlayerCollection extends BaseCollection {
    public function append($value): void {
        if (!$value instanceof Player) {
            throw new \InvalidArgumentException("Value must be an instance of Player");
        }
        parent::append($value);
    }
}
