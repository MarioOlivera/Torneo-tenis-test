<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;

abstract class BaseEntity implements \JsonSerializable {
    protected ?int $id;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;

    public function __construct(?int $id, \DateTimeImmutable $created_at = new DateTimeImmutable(), \DateTimeImmutable $updated_at = new DateTimeImmutable()) {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int { return $this->id; }
    protected function setId(?int $id): void { 
      $this->id = $id; 

      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function setCreatedAt(DateTimeImmutable $datetime): void { 
      $this->created_at = $datetime; 
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getCreatedAt(): \DateTimeImmutable {
        return $this->created_at;
    }

    protected function setUpdatedAt(\DateTimeImmutable $updated_at): void {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt(): \DateTimeImmutable {
        return $this->updated_at;
    }

    abstract public function toArray(): array;

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}
