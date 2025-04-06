<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;
use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Enums\TournamentStatus;

class Tournament{
    private int $id;
    private string $name;
    private TournamentCategory $category;
    private TournamentStatus $status;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    public function __construct(
        ?int $id,
        string $name,  
        TournamentCategory $category,
        TournamentStatus $status,
        DateTimeImmutable $created_at = new DateTimeImmutable(),
        DateTimeImmutable $updated_at = new DateTimeImmutable()
    )
    {
        $this->setId($id);
        $this->setName($name);
        $this->setCategory($category);
        $this->setStatus($status);
        $this->setCreatedAt($created_at);
        $this->setUpdatedAt($updated_at);
    }

    private function setId(?int $id){
        $this->id = $id;
    }

    public function getId() : int{
        return $this->id;
    }

    public function getName(): string { return $this->name; }

    public function setName(string $name): void { 
      $this->name = $this->normalizeName($name); 

      $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function setCategory(TournamentCategory $category)
    {
        $this->category = $category;

        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getCategory() : TournamentCategory
    {
        return $this->category;
    }

    public function setStatus(TournamentStatus $status)
    {
        $this->status = $status;

        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getStatus() : TournamentStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable { return $this->created_at; }
    public function setCreatedAt(DateTimeImmutable $datetime): void { $this->created_at = $datetime; }

    public function getUpdatedAt(): DateTimeImmutable { return $this->updated_at; }
    private function setUpdatedAt(DateTimeImmutable $datetime): void { 
        $this->updated_at = $datetime; 
    }

    private function normalizeName(string $name): string {
        $name = trim($name);
        $name = strtolower($name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $name;
    }
}