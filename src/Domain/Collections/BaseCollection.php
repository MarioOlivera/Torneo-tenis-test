<?php
namespace Src\Domain\Collections;

abstract class BaseCollection extends \ArrayObject implements \JsonSerializable {

    public function toArray(): array {
        return $this->getArrayCopy();
    }

    public function jsonSerialize(): mixed {
        return $this->toArray();
    }
}
