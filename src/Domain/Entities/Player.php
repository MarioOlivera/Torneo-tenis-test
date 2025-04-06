<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;
use Src\Domain\Enums\Gender;

class Player implements \JsonSerializable
{
    private ?int $id;
    private string $name;
    private int $skill_level;
    private ?int $strength = null;
    private ?int $speed = null;
    private ?int $reaction_time = null;
    private Gender $gender;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    public function __construct(
        ?int $id,
        string $name,
        int $skill_level,
        Gender $gender,
        ?int $strength = null,
        ?int $speed = null,
        ?int $reaction_time = null,
        DateTimeImmutable $created_at = new DateTimeImmutable(),
        DateTimeImmutable $updated_at = new DateTimeImmutable()
    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setSkillLevel($skill_level);
        $this->setGender($gender);
        $this->validateGenderAttributes($gender,$strength,$speed,$reaction_time);
        $this->setCreatedAt($created_at);
        $this->setUpdatedAt($updated_at);
    }

    public function getId(): ?int { return $this->id; }

    private function setId(?int $id): void { 
      $this->id = $id; 

      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getName(): string { return $this->name; }

    public function setName(string $name): void { 
      if (!preg_match('/^[\p{L}\s]+$/u', $name)) {
        throw new \InvalidArgumentException("Name must contain only letters and spaces.");
      }

      $this->name = $this->normalizeName($name); 
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getSkillLevel(): int { return $this->skill_level; }

    public function setSkillLevel(int $skill_level): void 
    {
        if ($skill_level < 0 || $skill_level > 100) {
            throw new \InvalidArgumentException("Skill level must be between 0 and 100.");
        }

        $this->skill_level = $skill_level;

        $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getStrength(): ?int { return $this->strength; }

    public function setStrength(?int $strength): void { 
      if ($strength < 0 || $strength > 100) {
        throw new \InvalidArgumentException("Strength must be between 0 and 100.");
      }

      $this->strength = $strength;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getSpeed(): ?int { return $this->speed; }

    public function setSpeed(?int $speed): void { 
      if ($speed < 0 || $speed > 100) {
        throw new \InvalidArgumentException("Speed must be between 0 and 100.");
      }

      $this->speed = $speed;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getReactionTime(): ?int { return $this->reaction_time; }
    
    public function setReactionTime(?int $reaction_time): void { 
      if ($reaction_time < 0 || $reaction_time > 100) {
        throw new \InvalidArgumentException("Reaction time must be between 0 and 100.");
      }

      $this->reaction_time = $reaction_time;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getGender(): Gender { return $this->gender; }

    public function setGender(Gender $gender): void { 
      $this->gender = $gender; 
    
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getCreatedAt(): DateTimeImmutable { return $this->created_at; }

    public function setCreatedAt(DateTimeImmutable $datetime): void { 
      $this->created_at = $datetime; 
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getUpdatedAt(): DateTimeImmutable { return $this->updated_at; }

    private function setUpdatedAt(DateTimeImmutable $datetime): void { 
      $this->updated_at = $datetime; 
    }

    private function validateGenderAttributes(
      Gender $gender, 
      ?int $strength, 
      ?int $speed, 
      ?int $reaction_time
    ): void {

      if ($gender->isMale() && (is_null($strength) || is_null($speed))) 
      {
        throw new \InvalidArgumentException("Male players require strength and speed.");
      }

      if ($gender->isFemale() && is_null($reaction_time)) 
      {
        throw new \InvalidArgumentException("Female players require reaction time.");
      }

      $this->strength = $strength;
      $this->speed = $speed;
      $this->reaction_time = $reaction_time;
    }

    public function toArray(): array {

      return [
        'id' => $this->id,
        'name' => $this->name,
        'skill_level' => $this->skill_level,
        'strength' => $this->strength,
        'speed' => $this->speed,
        'reaction_time' => $this->reaction_time,
        'gender_id' => $this->gender->value,
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
      ];
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }

    private function normalizeName(string $name): string {
      $name = trim($name);
      $name = preg_replace('/\s+/', ' ', $name);
      return ucwords(strtolower($name));
    }
}
