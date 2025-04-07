<?php
namespace Src\Domain\Entities;

use DateTimeImmutable;
use Src\Domain\Enums\Gender;

use Src\Domain\Exceptions\MalePlayersRequireStrengthAndSpeedException;
use Src\Domain\Exceptions\FemalePlayersRequireReactionTimeException;
use Src\Domain\Exceptions\InvalidPlayerNameException;
use Src\Domain\Exceptions\SkillLevelOutOfRangeException;
use Src\Domain\Exceptions\StrengthOutOfRangeException;
use Src\Domain\Exceptions\SpeedOutOfRangeException;
use Src\Domain\Exceptions\ReactionTimeOutOfRangeException;
class Player extends BaseEntity
{
    private string $name;
    private int $skill_level;
    private ?int $strength = null;
    private ?int $speed = null;
    private ?int $reaction_time = null;
    private Gender $gender;

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
        $this->validateGenderAttributes($gender,$strength,$speed,$reaction_time);
        $this->setGender($gender);
        $this->setCreatedAt($created_at);
        $this->setUpdatedAt($updated_at);
    }

    public function getName(): string { return $this->name; }

    public function setName(string $name): void { 
      if (!preg_match('/^[\p{L}\s]+$/u', $name)) {
        throw new InvalidPlayerNameException("Name must contain only letters and spaces.");
      }

      $this->name = $this->normalizeName($name); 
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getSkillLevel(): int { return $this->skill_level; }

    public function setSkillLevel(int $skill_level): void 
    {
        if ($skill_level < 0 || $skill_level > 100) {
            throw new SkillLevelOutOfRangeException("Skill level must be between 0 and 100.");
        }

        $this->skill_level = $skill_level;

        $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getStrength(): ?int { return $this->strength; }

    public function setStrength(?int $strength): void { 
      if ($strength < 0 || $strength > 100) {
        throw new StrengthOutOfRangeException("Strength must be between 0 and 100.");
      }

      $this->strength = $strength;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getSpeed(): ?int { return $this->speed; }

    public function setSpeed(?int $speed): void { 
      if ($speed < 0 || $speed > 100) {
        throw new SpeedOutOfRangeException("Speed must be between 0 and 100.");
      }

      $this->speed = $speed;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getReactionTime(): ?int { return $this->reaction_time; }
    
    public function setReactionTime(?int $reaction_time): void { 
      if ($reaction_time < 0 || $reaction_time > 100) {
        throw new ReactionTimeOutOfRangeException("Reaction time must be between 0 and 100.");
      }

      $this->reaction_time = $reaction_time;
      
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }

    public function getGender(): Gender { return $this->gender; }

    public function setGender(Gender $gender): void { 
      $this->validateGenderAttributes($gender, $this->strength, $this->speed, $this->reaction_time);

      $this->gender = $gender; 
    
      $this->setUpdatedAt(new DateTimeImmutable()); 
    }
    private function validateGenderAttributes(
      Gender $gender, 
      ?int $strength, 
      ?int $speed, 
      ?int $reaction_time
    ): void {

      if ($gender->isMale() && (is_null($strength) || is_null($speed))) 
      {
        throw new MalePlayersRequireStrengthAndSpeedException("Male players require strength and speed.");
      }

      if ($gender->isFemale() && is_null($reaction_time)) 
      {
        throw new FemalePlayersRequireReactionTimeException("Female players require reaction time.");
      }

      $this->strength = $strength;
      $this->speed = $speed;
      $this->reaction_time = $reaction_time;
    }
    private function normalizeName(string $name): string {
      $name = trim($name);
      $name = preg_replace('/\s+/', ' ', $name);
      return ucwords(strtolower($name));
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
}
