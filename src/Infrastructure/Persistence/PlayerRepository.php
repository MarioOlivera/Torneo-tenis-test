<?php
namespace Src\Infrastructure\Persistence;

use Src\Domain\Entities\Player;
use Src\Domain\Repositories\PlayerRepositoryInterface;
use Src\Domain\Enums\Gender;

class PlayerRepository implements PlayerRepositoryInterface {
    public function __construct(private \mysqli $connection) {}

    public function findAll(): array
    {
        $query = "SELECT * FROM players";
        $result = $this->connection->query($query);

        $players = [];
        while ($row = $result->fetch_assoc()) {
            $players[] = new Player(
                $row['id'], 
                $row['name'], 
                $row['skill_level'], 
                Gender::from($row['gender_id']), 
                $row['strength'], 
                $row['speed'], 
                $row['reaction_time'], 
                new \DateTimeImmutable($row['created_at']), 
                new \DateTimeImmutable($row['updated_at'])
            );
        }

        return $players;
    }

    public function save(Player $player): Player {
        $stmt = $this->connection->prepare("
            INSERT INTO players (name, skill_level, gender_id, strength, speed, reaction_time, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                skill_level = VALUES(skill_level),
                gender_id = VALUES(gender_id),
                strength = VALUES(strength),
                speed = VALUES(speed),
                reaction_time = VALUES(reaction_time),
                created_at = VALUES(created_at),
                updated_at = VALUES(updated_at)
        ");

        $name = $player->getName();
        $skillLevel = $player->getSkillLevel();
        $genderValue = $player->getGender()->value;
        $strength = $player->getStrength();
        $speed = $player->getSpeed();
        $reactionTime = $player->getReactionTime();
        $created_at = $player->getCreatedAt()->format('Y-m-d H:i:s');
        $updated_at = $player->getUpdatedAt()->format('Y-m-d H:i:s');

        $stmt->bind_param(
            "siiiiiss",
            $name,
            $skillLevel,
            $genderValue,
            $strength,
            $speed,
            $reactionTime,
            $created_at,
            $updated_at
        );
        
        $stmt->execute();
        $playerId = $this->connection->insert_id;
        
        // Asigna el ID si es un nuevo jugador
        if ($player->getId() === null) {
            $reflection = new \ReflectionClass($player);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($player, $playerId);
        }

        return $player;
    }

    public function findById(int $id): ?Player {
        $stmt = $this->connection->prepare("
            SELECT * FROM players WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $data = $result->fetch_assoc();
        return new Player(
            $data['id'],
            $data['name'],
            $data['skill_level'],
            Gender::from($data['gender']),
            $data['strength'],
            $data['speed'],
            $data['reaction_time'],
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['updated_at'])
        );
    }

    public function delete(int $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM players WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}