<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;
use Src\Domain\Enums\TournamentCategory;
use Src\Domain\Enums\TournamentStatus;

class Tournament extends BaseEntity{
    private string $name;
    private TournamentCategory $category;
    private TournamentStatus $status;
    public function __construct(
        ?int $id,
        string $name,  
        TournamentCategory $category,
        TournamentStatus $status = TournamentStatus::PENDING,
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

    private function normalizeName(string $name): string {
        $name = trim($name);
        $name = strtolower($name);
        $name = preg_replace('/\s+/', ' ', $name);
        return ucwords($name);
    }

    public function toArray(): array {
       return [
          'id' => $this->id,
          'name' => $this->name,
          'category_id' => $this->category,
          'status_id' => $this->status,
          'created_at' => $this->created_at->format('Y-m-d H:i:s'),
          'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}